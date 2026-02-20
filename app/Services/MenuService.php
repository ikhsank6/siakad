<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class MenuService
{
    /**
     * Safely generate route URL - returns # if route doesn't exist
     */
    public static function safeRoute(?string $routeName): string
    {
        if (empty($routeName)) {
            return '#';
        }

        try {
            if (Route::has($routeName)) {
                return route($routeName);
            }
        } catch (\Exception $e) {
            // Route doesn't exist or has required parameters
        }

        return '#';
    }

    /**
     * Check if route exists
     */
    public static function routeExists(?string $routeName): bool
    {
        if (empty($routeName)) {
            return false;
        }

        return Route::has($routeName);
    }

    /**
     * Cache TTL in seconds (1 hour)
     */
    protected const CACHE_TTL = 3600;

    /**
     * Cache key prefix
     */
    protected const CACHE_PREFIX = 'menu_tree_role_';

    /**
     * Get menus for a specific role.
     */
    public function getMenusForRole(int $roleId): Collection
    {
        $role = Role::with(['menus' => function ($query) {
            $query->where('is_active', true)
                ->orderBy('order');
        }])->find($roleId);

        if (! $role) {
            return collect();
        }

        return $role->menus;
    }

    /**
     * Build a nested menu tree from a flat collection of menus.
     */
    public function buildMenuTree(Collection $menus): array
    {
        $menuById = [];
        $tree = [];

        // First pass: index all menus by ID
        foreach ($menus as $menu) {
            $menuById[$menu->id] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'slug' => $menu->slug,
                'icon' => $menu->icon,
                'route' => $menu->route,
                'order' => $menu->order,
                'parent_id' => $menu->parent_id,
                'children' => [],
            ];
        }

        // Second pass: build the tree structure
        foreach ($menuById as $id => $menu) {
            if ($menu['parent_id'] === null) {
                $tree[$id] = &$menuById[$id];
            } else {
                if (isset($menuById[$menu['parent_id']])) {
                    $menuById[$menu['parent_id']]['children'][$id] = &$menuById[$id];
                }
            }
        }

        // Sort by order and convert to indexed arrays
        return $this->sortAndIndexTree($tree);
    }

    /**
     * Sort tree nodes by order and convert to indexed arrays.
     */
    protected function sortAndIndexTree(array $tree): array
    {
        usort($tree, fn ($a, $b) => $a['order'] <=> $b['order']);

        foreach ($tree as &$node) {
            if (! empty($node['children'])) {
                $node['children'] = $this->sortAndIndexTree($node['children']);
            }
        }

        return $tree;
    }

    /**
     * Get cached menu tree for a role.
     */
    public function getCachedMenuTree(int $roleId): array
    {
        $cacheKey = self::CACHE_PREFIX.$roleId;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($roleId) {
            $menus = $this->getMenusForRole($roleId);

            return $this->buildMenuTree($menus);
        });
    }

    /**
     * Clear menu cache for a specific role or all roles.
     */
    public function clearMenuCache(?int $roleId = null): void
    {
        if ($roleId !== null) {
            Cache::forget(self::CACHE_PREFIX.$roleId);
        } else {
            // Clear all role menu caches
            $roles = Role::pluck('id');
            foreach ($roles as $id) {
                Cache::forget(self::CACHE_PREFIX.$id);
            }
        }
    }

    /**
     * Get the menu tree for the currently authenticated user.
     */
    public function getMenuTreeForUser(): array
    {
        $user = auth()->user();

        if (! $user || ! $user->role_id) {
            return [];
        }

        return $this->getCachedMenuTree($user->role_id);
    }

    /**
     * Check if user has access to a specific route.
     */
    public function userHasAccessToRoute(string $routeName): bool
    {
        $user = auth()->user();

        if (! $user || ! $user->role_id) {
            return false;
        }

        $menus = $this->getMenusForRole($user->role_id);

        return $menus->contains('route', $routeName);
    }

    /**
     * Get active menu based on current route.
     */
    public function getActiveMenu(string $currentRoute): ?array
    {
        $user = auth()->user();

        if (! $user || ! $user->role_id) {
            return null;
        }

        $menus = $this->getMenusForRole($user->role_id);
        $menu = $menus->firstWhere('route', $currentRoute);

        if ($menu) {
            return [
                'id' => $menu->id,
                'name' => $menu->name,
                'slug' => $menu->slug,
                'parent_id' => $menu->parent_id,
            ];
        }

        return null;
    }
}
