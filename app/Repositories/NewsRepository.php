<?php

namespace App\Repositories;

use App\Models\News;
use App\Repositories\Contracts\NewsRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class NewsRepository extends BaseRepository implements NewsRepositoryInterface
{
    public function __construct(News $model)
    {
        parent::__construct($model);
    }

    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            if (isset($data['image'])) {
                $media = app(\App\Repositories\Contracts\MediaRepositoryInterface::class)->syncFromPath($data['image']);
                $data['media_id'] = $media?->id;
            }

            return parent::create($data);
        });
    }

    public function update(int $id, array $data): bool
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($id, $data) {
            $record = $this->findOrFail($id);

            if (isset($data['image'])) {
                $media = app(\App\Repositories\Contracts\MediaRepositoryInterface::class)->syncFromPath($data['image'], $record->media_id);
                $data['media_id'] = $media?->id;
            }

            return parent::update($id, $data);
        });
    }

    public function delete(int $id): bool
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($id) {
            $record = $this->findOrFail($id);

            if ($record->media_id) {
                app(\App\Repositories\Contracts\MediaRepositoryInterface::class)->deleteMedia($record->media_id);
            }

            return parent::delete($id);
        });
    }

    public function getPublished(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with('category')
            ->active()
            ->published()
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function getFeatured(int $limit = 5): Collection
    {
        return $this->model
            ->with('category')
            ->active()
            ->published()
            ->featured()
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function getByCategory(int $categoryId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with('category')
            ->where('news_category_id', $categoryId)
            ->active()
            ->published()
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function searchByTerm(?string $term, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->with('category');

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('excerpt', 'like', "%{$term}%")
                    ->orWhere('content', 'like', "%{$term}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function findBySlug(string $slug): ?News
    {
        return $this->model
            ->with('category')
            ->where('slug', $slug)
            ->active()
            ->published()
            ->first();
    }

    public function getLatest(int $limit = 6): Collection
    {
        return $this->model
            ->with('category')
            ->where('is_active', true)
            ->latest('published_at')
            ->take($limit)
            ->get();
    }

    public function getActiveWithFilter(?string $categorySlug, int $perPage = 8): LengthAwarePaginator
    {
        return $this->model
            ->with('category')
            ->where('is_active', true)
            ->when($categorySlug, function ($query, $categorySlug) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
            })
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function getRecentExcept(string $exceptId, int $limit = 5): Collection
    {
        return $this->model
            ->with('category')
            ->where('is_active', true)
            ->where('id', '!=', $exceptId)
            ->latest('published_at')
            ->take($limit)
            ->get();
    }

    public function getRelated(string $categoryId, string $exceptId, int $limit = 4): Collection
    {
        return $this->model
            ->with('category')
            ->where('is_active', true)
            ->where('id', '!=', $exceptId)
            ->where('news_category_id', $categoryId)
            ->take($limit)
            ->get();
    }

    public function findActiveBySlugOrFail(string $slug): News
    {
        return $this->model
            ->with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }
}
