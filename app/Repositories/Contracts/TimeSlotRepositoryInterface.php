<?php

namespace App\Repositories\Contracts;

interface TimeSlotRepositoryInterface extends RepositoryInterface
{
    public function getByDay(int $day);
    public function updateBreaks(array $breakTimes);
    public function truncate();
}
