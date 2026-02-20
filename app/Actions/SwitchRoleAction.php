<?php

namespace App\Actions;

use App\Models\Role;
use App\Services\MenuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SwitchRoleAction
{
    public function __construct(
        protected MenuService $menuService
    ) {}

    /**
     * Switch the authenticated user's active role.
     */
    public function __invoke(Role $role): RedirectResponse
    {
        $user = Auth::user();

        // Verify user has this role
        if (! $user->roles->contains('id', $role->id)) {
            return back()->with('error', 'Unauthorized role switch.');
        }

        $user->setActiveRole($role);
        $this->menuService->clearMenuCache($role->id);

        return redirect()->route('dashboard')->with('success', "Switched to active role: {$role->name}");
    }
}
