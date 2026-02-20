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
     * Lock schedules for an academic year.
     */
    public function lock(int $academicYearId): bool;
}
