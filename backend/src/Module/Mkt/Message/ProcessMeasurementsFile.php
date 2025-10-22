<?php

namespace App\Module\Mkt\Message;

use App\Module\Mkt\ValueObject\MeasurementsFilePayload;

final class ProcessMeasurementsFile
{
    public function __construct(
        public readonly MeasurementsFilePayload $payload,
    ) {}
}
