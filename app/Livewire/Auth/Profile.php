<?php

namespace App\Livewire\Auth;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\MenuService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Profile')]
class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public $avatar;

    public ?string $currentAvatar = null;

    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->currentAvatar = $user->avatar ?? null;
    }

    /**
     * Update name on change
     */
    public function updateName(UserRepositoryInterface $userRepository): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $userRepository->update(Auth::id(), ['name' => $this->name]);
            $this->dispatch('notify', text: 'Name updated successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    /**
     * Update email on change
     */
    public function updateEmail(UserRepositoryInterface $userRepository): void
    {
        $this->validate([
            'email' => 'required|email|max:255|unique:users,email,'.Auth::id(),
        ]);

        try {
            $userRepository->update(Auth::id(), ['email' => $this->email]);
            $this->dispatch('notify', text: 'Email updated successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    /**
     * Handle avatar upload - triggered automatically when avatar property changes
     */
    public function updatedAvatar(UserRepositoryInterface $userRepository): void
    {
        $this->validate([
            'avatar' => 'image|max:2048',
        ]);

        try {
            $avatarPath = $userRepository->updateAvatar(Auth::id(), $this->avatar);
            $this->currentAvatar = $avatarPath;
            $this->reset('avatar');

            $this->dispatch('notify', text: 'Photo updated successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    /**
     * Delete current avatar
     */
    public function deleteAvatar(UserRepositoryInterface $userRepository): void
    {
        try {
            $userRepository->deleteAvatar(Auth::id());
            $this->currentAvatar = null;
            $this->dispatch('notify', text: 'Photo removed successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    /**
     * Switch currently active role
     */
    public function switchRole($roleId, MenuService $menuService, UserRepositoryInterface $userRepository): void
    {
        try {
            if ($userRepository->setActiveRole(Auth::id(), $roleId)) {
                // Clear menu cache to reflect new role
                $menuService->clearMenuCache($roleId);

                $this->dispatch('notify', text: 'Role switched successfully.', variant: 'success');
                $this->js('window.location.reload()');
            } else {
                $this->dispatch('notify', text: 'Unauthorized role switch.', variant: 'danger');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    /**
     * Set default role for login
     */
    public function setDefaultRole($roleId, UserRepositoryInterface $userRepository): void
    {
        try {
            $userRepository->setDefaultRole(Auth::id(), $roleId);

            // Refresh the user instance relationship to update the UI
            Auth::user()->load('roles');

            $this->dispatch('notify', text: 'Default role updated successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.auth.profile', [
            'userRoles' => Auth::user()->roles,
            'activeRoleId' => Auth::user()->role_id,
        ]);
    }
}
