<?php

namespace App\Livewire\Menus;

use App\Models\Menu;
use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Services\MenuService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Menu Access')]
class RoleMenuAccess extends Component
{
    #[Url]
    public $search = '';

    public ?int $selectedRoleId = null;

    public array $selectedMenus = [];

    // Store previous state to detect changes
    public array $oldSelectedMenus = [];

    protected RoleRepositoryInterface $roleRepository;

    protected MenuRepositoryInterface $menuRepository;

    public function boot(
        RoleRepositoryInterface $roleRepository,
        MenuRepositoryInterface $menuRepository
    ): void {
        $this->roleRepository = $roleRepository;
        $this->menuRepository = $menuRepository;
    }

    public function selectRole(int $roleId): void
    {
        $this->selectedRoleId = $roleId;

        // Load current menu access for this role
        $role = $this->roleRepository->find($roleId);
        $role->load('menus');
        $this->selectedMenus = $role->menus->pluck('id')->toArray();
        $this->oldSelectedMenus = $this->selectedMenus;
    }

    public function updatedSelectedMenus()
    {
        // Detect what was added or removed
        $added = array_diff($this->selectedMenus, $this->oldSelectedMenus);
        $removed = array_diff($this->oldSelectedMenus, $this->selectedMenus);

        if (! empty($added)) {
            foreach ($added as $menuId) {
                // If a child is checked, check the parent
                $menu = $this->menuRepository->find($menuId);
                if ($menu && $menu->parent_id && ! in_array($menu->parent_id, $this->selectedMenus)) {
                    $this->selectedMenus[] = $menu->parent_id;
                }
            }
        }

        if (! empty($removed)) {
            foreach ($removed as $menuId) {
                // If a parent is unchecked, uncheck all children
                $childrenIds = Menu::where('parent_id', $menuId)->pluck('id')->toArray();
                if (! empty($childrenIds)) {
                    $this->selectedMenus = array_values(array_diff($this->selectedMenus, $childrenIds));
                }
            }
        }

        // Update old state
        $this->selectedMenus = array_values(array_unique($this->selectedMenus));
        $this->oldSelectedMenus = $this->selectedMenus;
    }

    public function saveMenuAccess(): void
    {
        if (! $this->selectedRoleId) {
            $this->dispatch('notify', text: 'Please select a role first.', variant: 'danger');

            return;
        }

        try {
            $role = DB::transaction(function () {
                $role = $this->roleRepository->findOrFail($this->selectedRoleId);
                $this->roleRepository->syncMenus($this->selectedRoleId, $this->selectedMenus);

                // Clear menu cache for this role
                $menuService = app(MenuService::class);
                $menuService->clearMenuCache($this->selectedRoleId);

                return $role;
            });

            $this->dispatch('notify', text: 'Menu access updated successfully for '.$role->name, variant: 'success');

            // Refresh the page to update sidebar (layout uses MenuService directly)
            $this->js('setTimeout(() => window.location.reload(), 1000)');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function backToRoles(): void
    {
        $this->selectedRoleId = null;
        $this->selectedMenus = [];
    }

    public function render()
    {
        $roles = $this->roleRepository->allWithUserCount();

        if ($this->search) {
            $roles = $roles->filter(function ($role) {
                return str_contains(strtolower($role->name), strtolower($this->search))
                    || str_contains(strtolower($role->slug), strtolower($this->search));
            });
        }

        $menus = $this->menuRepository->getMenuTree();

        $selectedRole = $this->selectedRoleId
            ? $this->roleRepository->find($this->selectedRoleId)
            : null;

        return view('livewire.menus.role-menu-access', [
            'roles' => $roles,
            'menus' => $menus,
            'selectedRole' => $selectedRole,
        ]);
    }
}
