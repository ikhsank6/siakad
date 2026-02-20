<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface MenuRepositoryInterface extends RepositoryInterface
{
    /**
     * Search menus with parent relation.
     */
    public function searchWithParent(?string $term, int $perPage = 50): LengthAwarePaginator;

    /**
     * Get root menus with children (for tree view).
     */
    public function getMenuTree(): Collection;

    /**
     * Get next order value.
     */
    public function getNextOrder(): int;

    /**
     * Update menu order.
     */
    public function updateOrder(array $orderedIds): void;

    /**
     * Update menu parent.
     */
    public function updateParent(int $menuId, ?int $newParentId): void;
}
