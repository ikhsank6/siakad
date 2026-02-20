<?php

namespace App\Livewire\Auth;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Change Password')]
class ChangePassword extends Component
{
    use \App\Livewire\Concerns\WithPasswordValidation;

    #[Rule('required|current_password')]
    public string $current_password = '';

    public string $password = '';

    public function rules()
    {
        return [
            'current_password' => 'required|current_password',
            'password' => $this->getPasswordRules(),
        ];
    }

    public string $password_confirmation = '';

    /**
     * Get the rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate('change-password|'.Auth::id().'|'.request()->ip());
    }

    /**
     * Ensure the request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'current_password' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function changePassword(UserRepositoryInterface $userRepository): void
    {
        $this->validate();

        try {
            $this->ensureIsNotRateLimited();

            $userRepository->updatePassword(Auth::id(), $this->password);

            RateLimiter::clear($this->throttleKey());

            $this->reset(['current_password', 'password', 'password_confirmation']);

            $this->dispatch('notify', text: 'Password changed successfully.', variant: 'success');
        } catch (ValidationException $e) {
            // Hit rate limiter on validation failure (likely current_password)
            RateLimiter::hit($this->throttleKey(), 300); // 5 minutes

            if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
                $this->dispatch('notify', text: 'Too many attempts. Please try again later.', variant: 'danger');
            }

            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.auth.change-password');
    }
}
