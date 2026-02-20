<?php

namespace App\Repositories;

use App\Models\NewsCategory;
use App\Repositories\Contracts\NewsCategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class NewsCategoryRepository extends BaseRepository implements NewsCategoryRepositoryInterface
{
    public function __construct(NewsCategory $model)
    {
        parent::__construct($model);
    }

    public function getActive(): Collection
    {
        return $this->model->active()->get();
    }

    public function searchByTerm(?string $term, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function getForDropdown(): Collection
    {
        return $this->model->active()->orderBy('name')->get(['id', 'name']);
    }

    public function getActiveWithNewsCount(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->withCount(['news' => fn ($q) => $q->where('is_active', true)])
            ->get();
    }
}
