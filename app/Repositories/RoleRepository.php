<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    /**
     * Search roles with user count.
     */
    public function searchWithUserCount(?string $term, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->withCount('users');

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('slug', 'like', "%{$term}%");
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Get all roles with user count (no pagination).
     */
    public function allWithUserCount(): Collection
    {
        return $this->model->withCount('users')->get();
    }

    /**
     * Find role by slug.
     */
    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)->first();
    }

    /**
     * Sync menus for a role.
     */
    public function syncMenus(int $roleId, array $menuIds): void
    {
        DB::transaction(function () use ($roleId, $menuIds) {
            $role = $this->findOrFail($roleId);
            $role->menus()->sync($menuIds);
        });
    }
}
