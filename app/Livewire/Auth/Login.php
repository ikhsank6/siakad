<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.auth')]
#[Title('Login')]
class Login extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required|min:6')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Get the rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }

    /**
     * Ensure login request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());
        $minutes = ceil($seconds / 60);

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => $minutes,
            ]),
        ]);
    }

    public function login(): void
    {
        try {
            $this->validate();
            $this->ensureIsNotRateLimited();

            if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
                RateLimiter::hit($this->throttleKey(), 300); // 5 minutes = 300 seconds

                $this->addError('email', 'These credentials do not match our records.');

                // No need for dispatch('notify') here, addError handles the message
                return;
            }

            RateLimiter::clear($this->throttleKey());

            $user = Auth::user();

            // Check if email is verified
            if (! $user->hasVerifiedEmail()) {
                Auth::logout();
                $this->addError('email', 'Please verify your email address before logging in. Check your inbox for the verification link.');

                // No need for dispatch('notify') here, addError handles the message
                return;
            }

            if (! $user->is_active) {
                Auth::logout();
                $this->addError('email', 'Your account has been deactivated.');
                $this->dispatch('notify', text: 'Account deactivated.', variant: 'danger');

                return;
            }

            // Set active role to default role on login
            $defaultRole = $user->getDefaultRole();
            if ($defaultRole) {
                $user->update(['role_id' => $defaultRole->id]);
            }

            session()->regenerate();
            $this->dispatch('notify', text: 'Welcome back!', variant: 'success');

            $this->redirect(route('dashboard'), navigate: true);
        } catch (ValidationException $e) {
            // Re-throw standard validation errors so Livewire can show inline messages
            // Only dispatch a global notification if it's a rate limit/throttle error
            if (RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
                $this->dispatch('notify', text: 'Too many login attempts. Please try again later.', variant: 'danger');
            }

            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
