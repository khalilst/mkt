<?php

namespace App\Module\Mkt\MessageHandler;

use App\Module\Mkt\Action\CalculateMktAction;
use App\Module\Mkt\Action\MeasurementBatchStoreAction;
use App\Module\Mkt\Dto\MeasurementBatchStoreDto;
use App\Module\Mkt\Dto\RawMeasurementDto;
use App\Module\Mkt\Entity\MeasurementSet;
use App\Module\Mkt\Message\ProcessMeasurementsFile;
use App\Module\Mkt\Repository\MeasurementSetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use League\Csv\Reader;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsMessageHandler]
final class ProcessMeasurementsFileHandler
{
    const MAX_BATCH_SIZE = 100;

    private MeasurementSet $measurementSet;
    private int $validMeasurementCount = 0;
    private int $totalMeasurementCount = 0;

    public function __construct(
        private MeasurementSetRepository $measurementSetRepository,
        private ValidatorInterface $validator,
        private MeasurementBatchStoreAction $measurementBatchStoreAction,
        private CalculateMktAction $calculateMktAction,
    ) {}

    public function __invoke(ProcessMeasurementsFile $message): void
    {
        $this->measurementSet = $this->measurementSetRepository->find($message->payload->measurementSetId);

        if (!$this->measurementSet) {
            return;
        }

        $this->storeMeasurementFile($message->payload->measurementsFilePath);

        $this->measurementSetRepository->updateMkt(
            measurementSetId: $this->measurementSet->getId(),
            mkt: $this->calculateMktAction->execute($this->measurementSet->getId()),
        );
    }

    private function storeMeasurementFile(string $path): void
    {
        $csv = Reader::from($path);
        $csv->setHeaderOffset(0);

        $batch = [];
        foreach ($csv->getRecords() as $record) {
            $this->totalMeasurementCount++;

            try {
                $dto = RawMeasurementDto::fromRawData($record['measured_at'], $record['temprature']);
            } catch (\Throwable $th) {
                continue;
            }

            $violations = $this->validator->validate($dto);

            if (count($violations)) {
                continue;
            }

            $batch[] = $dto;
            $this->validMeasurementCount++;

            if (count($batch) === self::MAX_BATCH_SIZE) {
                $this->storeMeasurementBatch($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            $this->storeMeasurementBatch($batch);
        }

        unlink($path);
    }

    /**
     * @param array<array-key, array<RawMeasurementDto>>
     */
    private function storeMeasurementBatch(array $batch): void
    {
        $this->measurementBatchStoreAction->execute(
            new MeasurementBatchStoreDto(
                measurementSetId: $this->measurementSet->getId(),
                batch: new ArrayCollection($batch),
            ),
        );
    }
}
