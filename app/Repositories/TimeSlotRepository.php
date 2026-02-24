<?php

namespace App\Repositories;

use App\Models\TimeSlot;
use App\Repositories\Contracts\TimeSlotRepositoryInterface;

class TimeSlotRepository extends BaseRepository implements TimeSlotRepositoryInterface
{
    public function __construct(TimeSlot $model)
    {
        parent::__construct($model);
    }

    public function getByDay(int $day)
    {
        return $this->model->where('day', $day)->orderBy('start_time')->get();
    }

    public function updateBreaks(array $breakTimes)
    {
        $this->model->query()->update(['is_break' => false]);

        foreach ($breakTimes as $break) {
            $this->model->where('start_time', '>=', $break['start'] . ':00')
                ->where('end_time', '<=', $break['end'] . ':00')
                ->update(['is_break' => true]);
        }
    }

    public function truncate()
    {
        $this->model->truncate();
    }
}
