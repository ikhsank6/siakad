<?php

namespace App\Repositories;

use App\Models\AcademicYear;
use App\Repositories\Contracts\AcademicYearRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AcademicYearRepository extends BaseRepository implements AcademicYearRepositoryInterface
{
    public function __construct(AcademicYear $model)
    {
        parent::__construct($model);
    }

    public function getActiveYear()
    {
        return $this->model->where('is_active', true)->first();
    }

    public function setActiveYear(int $id)
    {
        return DB::transaction(function () use ($id) {
            $this->model->where('is_active', true)->update(['is_active' => false]);
            $year = $this->findOrFail($id);
            $year->update(['is_active' => true]);
            return $year;
        });
    }
}
