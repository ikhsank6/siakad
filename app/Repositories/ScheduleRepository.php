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

    public function getTeacherSchedule(int $teacherId, int $academicYearId): \Illuminate\Support\Collection
    {
        return $this->model->with(['academicClass', 'subject', 'room', 'timeSlot'])
            ->where('teacher_id', $teacherId)
            ->where('academic_year_id', $academicYearId)
            ->whereIn('status', ['published', 'locked'])
            ->get();
    }

    public function lock(int $academicYearId): bool
    {
        return DB::transaction(function () use ($academicYearId) {
            return $this->model->where('academic_year_id', $academicYearId)
                ->where('status', 'published')
                ->update(['status' => 'locked']);
        });
    }

    public function getFilteredSchedules(int $academicYearId, array $filters = []): \Illuminate\Support\Collection
    {
        $query = $this->model->with(['academicClass', 'subject', 'room', 'teacher', 'timeSlot'])
            ->where('academic_year_id', $academicYearId)
            ->whereIn('status', ['published', 'locked']);

        if (!empty($filters['teacher_id'])) {
            $query->where('teacher_id', $filters['teacher_id']);
        }

        if (!empty($filters['class_id'])) {
            $query->where('class_id', $filters['class_id']);
        }

        if (!empty($filters['room_id'])) {
            $query->where('room_id', $filters['room_id']);
        }

        return $query->get();
    }
}
