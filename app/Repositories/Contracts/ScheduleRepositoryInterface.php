<?php

namespace App\Repositories\Contracts;

use App\Models\AcademicYear;

interface ScheduleRepositoryInterface extends RepositoryInterface
{
    /**
     * Clear draft schedules for a specific academic year.
     */
    public function clearDrafts(int $academicYearId): void;

    /**
     * Bulk insert schedules.
     */
    public function bulkInsert(array $items): bool;

    /**
     * Publish schedules for an academic year.
     */
    public function publish(int $academicYearId): bool;

    /**
     * Get published/locked schedule for a specific teacher and academic year.
     */
    public function getTeacherSchedule(int $teacherId, int $academicYearId): \Illuminate\Support\Collection;

    /**
     * Lock schedules for an academic year.
     */
    public function lock(int $academicYearId): bool;

    /**
     * Get filtered schedules.
     */
    public function getFilteredSchedules(int $academicYearId, array $filters = []): \Illuminate\Support\Collection;
    public function getSchedulesWithRelations(int $academicYearId, array $status, array $filters = []): \Illuminate\Support\Collection;
}
