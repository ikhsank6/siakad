<?php

namespace App\Repositories\Contracts;

interface TeacherRepositoryInterface extends RepositoryInterface
{
    public function getConfigs(int $academicYearId);
    public function updateConfig(int $teacherId, array $configData);
    public function findByUserId(int $userId);
    public function getTeachersBySubject($subjectId): \Illuminate\Support\Collection;
    public function getTeachersWithConfig(int $academicYearId): \Illuminate\Support\Collection;
}
