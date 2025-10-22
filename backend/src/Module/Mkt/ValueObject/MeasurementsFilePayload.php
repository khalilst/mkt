<?php

namespace App\Module\Mkt\ValueObject;

use JsonSerializable;

final class MeasurementsFilePayload implements JsonSerializable
{
    public function __construct(
        public readonly int $measurementSetId,
        public readonly string $measurementsFilePath,
        public readonly string $measurementsFileExtension,
    ) {}

    public static function make(...$args)
    {
        return new static(...$args);
    }

    public function jsonSerialize(): array
    {
        return [
            'measurementSetId' => $this->measurementSetId,
            'measurementsFilePath' => $this->measurementsFilePath,
            'measurementsFileExtension' => $this->measurementsFileExtension,
        ];
    }
}
