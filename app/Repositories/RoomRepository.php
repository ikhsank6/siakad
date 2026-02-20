<?php

namespace App\Repositories;

use App\Models\Room;
use App\Repositories\Contracts\RoomRepositoryInterface;

class RoomRepository extends BaseRepository implements RoomRepositoryInterface
{
    public function __construct(Room $model)
    {
        parent::__construct($model);
    }
}
