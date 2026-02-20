<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface RoleRepositoryInterface extends RepositoryInterface
{
    /**
     * Search roles with user count.
     */
    public function searchWithUserCount(?string $term, int $perPage = 10): LengthAwarePaginator;

    /**
     * Get all roles with user count (no pagination).
     */
    public function allWithUserCount(): Collection;

    /**
     * Find role by slug.
     */
    public function findBySlug(string $slug);

    /**
     * Sync menus for a role.
     */
    public function syncMenus(int $roleId, array $menuIds): void;
}
