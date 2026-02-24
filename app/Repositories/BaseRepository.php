<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records.
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Get paginated records.
     */
    public function paginate(int $perPage = 10, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->select($columns)->latest()->paginate($perPage);
    }

    /**
     * Find a record by ID.
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Find a record by UUID.
     */
    public function findByUuid(string $uuid): ?Model
    {
        return $this->model->where('uuid', $uuid)->first();
    }

    /**
     * Find a record by UUID or fail.
     */
    public function findByUuidOrFail(string $uuid): Model
    {
        return $this->model->where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Find a record by ID or fail.
     */
    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new record.
     */
    public function create(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            return $this->model->create($data);
        });
    }

    /**
     * Update an existing record.
     */
    public function update(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $record = $this->findOrFail($id);

            return $record->update($data);
        });
    }

    /**
     * Delete a record.
     */
    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $record = $this->findOrFail($id);

            return $record->delete();
        });
    }

    /**
     * Search records by given criteria.
     */
    public function search(array $searchable, ?string $term = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if ($term && count($searchable) > 0) {
            $query->where(function ($q) use ($searchable, $term) {
                foreach ($searchable as $column) {
                    $q->orWhere($column, 'like', "%{$term}%");
                }
            });
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find a record by criteria.
     */
    public function findOneBy(array $criteria): ?Model
    {
        return $this->model->where($criteria)->first();
    }

    /**
     * Get records by criteria.
     */
    public function findBy(array $criteria): Collection
    {
        return $this->model->where($criteria)->get();
    }

    /**
     * Get the underlying model.
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Create a new query builder.
     */
    public function query()
    {
        return $this->model->newQuery();
    }
}
