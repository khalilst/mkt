<?php

namespace App\Module\Mkt\Enum;

enum MeasurementSetStatus: int
{
    case InProgress = 0;
    case Completed = 1;
    case Failed = 2;

    public function label(): string
    {
        return match($this) {
            self::InProgress => 'in progress',
            self::Completed => 'completed',
            self::Failed => 'failed',
            default => 'none',
        };
    }
}
