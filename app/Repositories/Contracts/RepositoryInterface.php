<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * Get all records.
     */
    public function all(): Collection;

    /**
     * Get paginated records.
     */
    public function paginate(int $perPage = 10, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Find a record by ID.
     */
    public function find(int $id): ?Model;

    /**
     * Find a record by UUID.
     */
    public function findByUuid(string $uuid): ?Model;

    /**
     * Find a record by UUID or fail.
     */
    public function findByUuidOrFail(string $uuid): Model;

    /**
     * Find a record by ID or fail.
     */
    public function findOrFail(int $id): Model;

    /**
     * Create a new record.
     */
    public function create(array $data): Model;

    /**
     * Update an existing record.
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a record.
     */
    public function delete(int $id): bool;

    /**
     * Search records by given criteria.
     */
    public function search(array $searchable, ?string $term = null, int $perPage = 10): LengthAwarePaginator;

    /**
     * Find a record by criteria.
     */
    public function findOneBy(array $criteria): ?Model;

    /**
     * Get records by criteria.
     */
    public function findBy(array $criteria): Collection;

    /**
     * Create a new query builder.
     */
    public function query();
}
