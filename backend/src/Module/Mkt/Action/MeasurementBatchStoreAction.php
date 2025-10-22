<?php

namespace App\Module\Mkt\Action;

use App\Module\Mkt\Dto\MeasurementBatchStoreDto;
use App\Module\Mkt\Dto\RawMeasurementDto;
use Doctrine\DBAL\Connection;

final class MeasurementBatchStoreAction
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function execute(MeasurementBatchStoreDto $measurementBatchStoreDto): void
    {
        $sql = 'INSERT INTO `measurement` (`measurement_set_id`, `measured_at`, `temperature`) VALUES ';

        $setId = $measurementBatchStoreDto->measurementSetId;

        $values = $measurementBatchStoreDto->batch
            ->map(
                fn (RawMeasurementDto $rawDto) =>
                    "({$setId}, '{$rawDto->measuredAt}', {$rawDto->temperature})"
            )
            ->toArray();

        $this->connection->executeStatement($sql . implode(",", $values));
    }
}
