<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AboutUsRepositoryInterface extends RepositoryInterface
{
    public function getActive();

    public function getCached();

    public function searchByTerm(?string $term, int $perPage = 10): LengthAwarePaginator;
}
