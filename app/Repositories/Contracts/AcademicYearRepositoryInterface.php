<?php

namespace App\Repositories\Contracts;

interface AcademicYearRepositoryInterface extends RepositoryInterface
{
    public function getActiveYear();
    public function setActiveYear(int $id);
}
