<?php

namespace App\Repositories;

use App\Models\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ScheduleRepository extends BaseRepository implements ScheduleRepositoryInterface
{
    public function __construct(Schedule $model)
    {
        parent::__construct($model);
    }

    public function clearDrafts(int $academicYearId): void
    {
        DB::transaction(function () use ($academicYearId) {
            $this->model->where('academic_year_id', $academicYearId)
                ->where('status', 'draft')
                ->delete();
        });
    }

    public function bulkInsert(array $items): bool
    {
        return DB::transaction(function () use ($items) {
            if (empty($items)) return true;
            
            foreach (array_chunk($items, 100) as $chunk) {
                $this->model->insert($chunk);
            }
            return true;
        });
    }

    public function publish(int $academicYearId): bool
    {
        return DB::transaction(function () use ($academicYearId) {
            return $this->model->where('academic_year_id', $academicYearId)
                ->where('status', 'draft')
                ->update(['status' => 'published']);
        });
    }

    public function lock(int $academicYearId): bool
    {
        return DB::transaction(function () use ($academicYearId) {
            return $this->model->where('academic_year_id', $academicYearId)
                ->where('status', 'published')
                ->update(['status' => 'locked']);
        });
    }
}
