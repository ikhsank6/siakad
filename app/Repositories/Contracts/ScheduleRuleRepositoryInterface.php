<?php

namespace App\Repositories\Contracts;

interface ScheduleRuleRepositoryInterface extends RepositoryInterface
{
    public function getGlobalRules(int $academicYearId);
    public function updateGlobalRule(int $academicYearId, string $type, string $value);
}
