<?php

namespace App\Module\Mkt\Event;

use App\Module\Mkt\ValueObject\MeasurementsFilePayload;

class MeasurementSetCreatedEvent
{
    public function __construct(
        public readonly MeasurementsFilePayload $payload
    ) {}
}
