<?php

namespace App\Module\Mkt\Action;

use App\Module\Mkt\Entity\Measurement;
use App\Module\Mkt\Entity\MeasurementSet;
use App\Module\Mkt\Query\MeasurementChunkQuery;
use App\Module\Mkt\Repository\MeasurementSetRepository;
use Doctrine\Common\Collections\ArrayCollection;

final class CalculateMktAction
{
    const CHUNK_SIZE = 100;

    const ACTIVATION_ENERGY = 83144;   // J/mol
    const GAS_CONSTANT = 8.314462618;  // J/mol·K
    const KELVIN_OFFSET = 273.15;

    private int $timeSum = 0;
    private float $weightedSum = 0;
    private ?Measurement $previousMeasurement = null;

    public function __construct(
        private MeasurementChunkQuery $measurementChunkQuery,
        private MeasurementSetRepository $measurementSetRepository,
    ) {}

    public function execute(MeasurementSet $measurementSet): MeasurementSet
    {
        $this->measurementChunkQuery->getChunkByMeasuredAt(
            $measurementSet->getId(),
            self::CHUNK_SIZE,
            fn (ArrayCollection $measurements) => $measurements->map(
                fn (Measurement $measurement) => $this->calculateSums($measurement),
            ),
        );

        $measurementSet->setMkt(round($this->getMkt(), 2));
        $this->measurementSetRepository->save($measurementSet);

        return $measurementSet;
    }

    private function calculateSums(Measurement $measurement): void
    {
        $delta = 1;

        if ($this->previousMeasurement) {
            $t1 = $this->previousMeasurement->getMeasuredAt()->getTimestamp();
            $t2 = $measurement->getMeasuredAt()->getTimestamp();
            $delta = max($t2 - $t1, 1);
        }

        $temperatureInKelvin = $measurement->getTemperature() + self::KELVIN_OFFSET;
        $this->weightedSum += exp(-self::ACTIVATION_ENERGY / (self::GAS_CONSTANT * $temperatureInKelvin)) * $delta;
        $this->timeSum += $delta;

        $this->previousMeasurement = $measurement;
    }

    private function getMkt(): ?float
    {
        return $this->timeSum > 0
            ? -self::ACTIVATION_ENERGY / (self::GAS_CONSTANT * log($this->weightedSum / $this->timeSum)) - self::KELVIN_OFFSET
            : null;
    }
}
