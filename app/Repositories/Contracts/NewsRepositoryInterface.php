<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface NewsRepositoryInterface extends RepositoryInterface
{
    public function getPublished(int $perPage = 10): LengthAwarePaginator;

    public function getFeatured(int $limit = 5);

    public function getByCategory(int $categoryId, int $perPage = 10): LengthAwarePaginator;

    public function searchByTerm(?string $term, int $perPage = 10): LengthAwarePaginator;

    public function findBySlug(string $slug);

    /**
     * Get latest active news.
     */
    public function getLatest(int $limit = 6): Collection;

    /**
     * Get active news with optional category filter.
     */
    public function getActiveWithFilter(?string $categorySlug, int $perPage = 8): LengthAwarePaginator;

    /**
     * Get recent news excluding specific ID.
     */
    public function getRecentExcept(string $exceptId, int $limit = 5): Collection;

    /**
     * Get related news by category.
     */
    public function getRelated(string $categoryId, string $exceptId, int $limit = 4): Collection;

    /**
     * Find active news by slug or fail.
     */
    public function findActiveBySlugOrFail(string $slug);
}
