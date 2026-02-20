<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the menu.
     */
    public function view(User $user, Menu $menu): bool
    {
        if (! $user->role) {
            return false;
        }

        return $user->role->menus()->where('menus.id', $menu->id)->exists();
    }

    /**
     * Determine whether the user can access a route.
     */
    public function accessRoute(User $user, string $routeName): bool
    {
        if (! $user->role) {
            return false;
        }

        return $user->role->menus()->where('route', $routeName)->exists();
    }

    /**
     * Determine whether the user can create menus.
     */
    public function create(User $user): bool
    {
        return $this->hasMenuManagementAccess($user);
    }

    /**
     * Determine whether the user can update the menu.
     */
    public function update(User $user, Menu $menu): bool
    {
        return $this->hasMenuManagementAccess($user);
    }

    /**
     * Determine whether the user can delete the menu.
     */
    public function delete(User $user, Menu $menu): bool
    {
        return $this->hasMenuManagementAccess($user);
    }

    /**
     * Check if user has access to menu management.
     */
    protected function hasMenuManagementAccess(User $user): bool
    {
        if (! $user->role) {
            return false;
        }

        return $user->role->menus()->where('route', 'menus.index')->exists();
    }
}
