<?php

namespace App\Repositories\Contracts;

interface TeacherRepositoryInterface extends RepositoryInterface
{
    public function getConfigs(int $academicYearId);
    public function updateConfig(int $teacherId, array $configData);
}
