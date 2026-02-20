<?php

namespace App\Repositories;

use App\Models\AboutUs;
use App\Repositories\Contracts\AboutUsRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AboutUsRepository extends BaseRepository implements AboutUsRepositoryInterface
{
    public function __construct(AboutUs $model)
    {
        parent::__construct($model);
    }

    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            if (isset($data['logo'])) {
                $media = app(\App\Repositories\Contracts\MediaRepositoryInterface::class)->syncFromPath($data['logo']);
                $data['media_id'] = $media?->id;
            }

            return parent::create($data);
        });
    }

    public function update(int $id, array $data): bool
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($id, $data) {
            $record = $this->findOrFail($id);

            if (isset($data['logo'])) {
                $media = app(\App\Repositories\Contracts\MediaRepositoryInterface::class)->syncFromPath($data['logo'], $record->media_id);
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

    public function getActive(): ?AboutUs
    {
        return $this->model->active()->first();
    }

    public function getCached(): ?AboutUs
    {
        return $this->model::getCached();
    }

    public function searchByTerm(?string $term, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('company_name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('address', 'like', "%{$term}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }
}
