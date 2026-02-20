<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CarouselRepositoryInterface extends RepositoryInterface
{
    public function getActiveOrdered();

    public function searchByTerm(?string $term, int $perPage = 10): LengthAwarePaginator;

    public function updateOrder(array $orderedIds): void;
}
