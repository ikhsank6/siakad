<?php

namespace App\Livewire\Auth;

use App\Repositories\Contracts\UserRepositoryInterface;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.auth')]
#[Title('Register')]
class Register extends Component
{
    use \App\Livewire\Concerns\WithPasswordValidation;

    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|email|unique:users,email')]
    public string $email = '';

    public string $password = '';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => $this->getPasswordRules(),
        ];
    }

    public string $password_confirmation = '';

    public bool $registered = false;

    public function register(UserRepositoryInterface $userRepository): void
    {
        $this->validate();

        try {
            $userRepository->register([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
            ]);

            // Show success message
            $this->registered = true;
            $this->dispatch('notify', text: 'Registration successful! Please check your email to verify your account.', variant: 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
