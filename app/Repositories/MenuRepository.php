<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Repositories\Contracts\MenuRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MenuRepository extends BaseRepository implements MenuRepositoryInterface
{
    public function __construct(Menu $model)
    {
        parent::__construct($model);
    }

    /**
     * Search menus with parent relation.
     */
    public function searchWithParent(?string $term, int $perPage = 50): LengthAwarePaginator
    {
        $query = $this->model->with('parent');

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('slug', 'like', "%{$term}%");
            });
        }

        return $query->orderByRaw('COALESCE(parent_id, id), parent_id IS NOT NULL, `order`')
            ->paginate($perPage);
    }

    /**
     * Get root menus with children (for tree view).
     */
    public function getMenuTree(): Collection
    {
        return $this->model
            ->with('children')
            ->whereNull('parent_id')
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Get next order value.
     */
    public function getNextOrder(): int
    {
        return $this->model->max('order') + 1;
    }

    /**
     * Update menu order.
     */
    public function updateOrder(array $orderedIds): void
    {
        DB::transaction(function () use ($orderedIds) {
            foreach ($orderedIds as $index => $id) {
                $this->model->where('id', $id)->update(['order' => $index]);
            }
        });
    }

    /**
     * Update menu parent.
     */
    public function updateParent(int $menuId, ?int $newParentId): void
    {
        DB::transaction(function () use ($menuId, $newParentId) {
            // Get the max order for the new parent's children
            $maxOrder = $this->model
                ->where('parent_id', $newParentId)
                ->max('order') ?? -1;

            // Update the menu's parent and set order to last position
            $this->model->where('id', $menuId)->update([
                'parent_id' => $newParentId,
                'order' => $maxOrder + 1,
            ]);
        });
    }
}
