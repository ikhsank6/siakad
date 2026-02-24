<?php

namespace App\Repositories\Contracts;

interface StudentRepositoryInterface extends RepositoryInterface
{
    public function findByUserId(int $userId);
}
