<?php

namespace App\Repositories;

use App\Models\Carousel;
use App\Repositories\Contracts\CarouselRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CarouselRepository extends BaseRepository implements CarouselRepositoryInterface
{
    public function __construct(Carousel $model)
    {
        parent::__construct($model);
    }

    public function getActiveOrdered(): Collection
    {
        return $this->model->active()->ordered()->get();
    }

    public function searchByTerm(?string $term, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }

        return $query->ordered()->paginate($perPage);
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

    public function updateOrder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            $this->model->where('id', $id)->update(['order' => $index + 1]);
        }
    }
}
