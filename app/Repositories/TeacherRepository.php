<?php

namespace App\Repositories;

use App\Models\Teacher;
use App\Models\TeacherConfig;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TeacherRepository extends BaseRepository implements TeacherRepositoryInterface
{
    public function __construct(Teacher $model)
    {
        parent::__construct($model);
    }

    public function getConfigs(int $academicYearId)
    {
        return TeacherConfig::where('academic_year_id', $academicYearId)->get();
    }

    public function updateConfig(int $teacherId, array $configData)
    {
        return DB::transaction(function () use ($teacherId, $configData) {
            return TeacherConfig::updateOrCreate(
                [
                    'teacher_id' => $teacherId,
                    'academic_year_id' => $configData['academic_year_id']
                ],
                $configData
            );
        });
    }
}
