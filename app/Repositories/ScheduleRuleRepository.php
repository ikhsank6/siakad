<?php

namespace App\Repositories;

use App\Models\ScheduleRule;
use App\Repositories\Contracts\ScheduleRuleRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ScheduleRuleRepository extends BaseRepository implements ScheduleRuleRepositoryInterface
{
    public function __construct(ScheduleRule $model)
    {
        parent::__construct($model);
    }

    public function getGlobalRules(int $academicYearId)
    {
        return $this->model->where('academic_year_id', $academicYearId)->get();
    }

    public function updateGlobalRule(int $academicYearId, string $type, string $value)
    {
        return DB::transaction(function () use ($academicYearId, $type, $value) {
            return $this->model->updateOrCreate(
                [
                    'academic_year_id' => $academicYearId,
                    'rule_type' => $type
                ],
                ['value' => $value]
            );
        });
    }
}
