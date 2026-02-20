<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface NewsCategoryRepositoryInterface extends RepositoryInterface
{
    public function getActive();

    public function searchByTerm(?string $term, int $perPage = 10): LengthAwarePaginator;

    public function getForDropdown();

    /**
     * Get active categories with news count.
     */
    public function getActiveWithNewsCount(): Collection;
}
