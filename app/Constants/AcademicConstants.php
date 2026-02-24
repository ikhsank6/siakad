<?php

namespace App\Constants;

class AcademicConstants
{
    public const DAYS = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    public const DAY_SHORT_LABELS = [
        1 => 'Sen',
        2 => 'Sel',
        3 => 'Ra',
        4 => 'Ka',
        5 => 'Ju',
        6 => 'Sab',
    ];

    public const SCHEDULE_STATUS_DRAFT = 'draft';
    public const SCHEDULE_STATUS_PUBLISHED = 'published';
    public const SCHEDULE_STATUS_LOCKED = 'locked';
}
