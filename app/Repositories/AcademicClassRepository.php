<?php

namespace App\Repositories;

use App\Models\AcademicClass;
use App\Repositories\Contracts\AcademicClassRepositoryInterface;

class AcademicClassRepository extends BaseRepository implements AcademicClassRepositoryInterface
{
    public function __construct(AcademicClass $model)
    {
        parent::__construct($model);
    }
}
