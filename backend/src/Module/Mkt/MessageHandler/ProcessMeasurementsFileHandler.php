<?php

namespace App\Module\Mkt\MessageHandler;

use App\Module\Mkt\Action\CalculateMktAction;
use App\Module\Mkt\Action\MeasurementBatchStoreAction;
use App\Module\Mkt\Action\NotifyMktCalculatedAction;
use App\Module\Mkt\Dto\MeasurementBatchStoreDto;
use App\Module\Mkt\Dto\RawMeasurementDto;
use App\Module\Mkt\Entity\MeasurementSet;
use App\Module\Mkt\Enum\MeasurementSetStatus;
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

    private int $measurementSetId;

    public function __construct(
        private MeasurementSetRepository $measurementSetRepository,
        private ValidatorInterface $validator,
        private MeasurementBatchStoreAction $measurementBatchStoreAction,
        private CalculateMktAction $calculateMktAction,
        private NotifyMktCalculatedAction $notifyMktCalculated,
    ) {}

    public function __invoke(ProcessMeasurementsFile $message): void
    {
        $this->measurementSetId = $message->payload->measurementSetId;
        $measurementSet = $this->measurementSetRepository->find($this->measurementSetId);

        if (!$measurementSet) {
            return;
        }

        try {
            $this->storeMeasurementFile($message->payload->measurementsFilePath);

            $measurementSet->setMkt(
                $this->calculateMktAction->execute($this->measurementSetId),
            );

            $measurementSet->setStatus(MeasurementSetStatus::Completed);
        } catch (\Throwable $th) {
            $measurementSet->setStatus(MeasurementSetStatus::Failed);
        } finally {
            $this->measurementSetRepository->updateMkt($measurementSet);
            $this->notifyMktCalculated->execute($measurementSet);
        }
    }

    private function storeMeasurementFile(string $path): void
    {
        $totalMeasurementCount = 0;
        $validMeasurementCount = 0;

        $csv = Reader::from($path);
        $csv->setHeaderOffset(0);

        $batch = [];
        foreach ($csv->getRecords() as $record) {
            $totalMeasurementCount++;

            try {
                $dto = RawMeasurementDto::fromRawData(
                    $record['measured_at'] ?? null,
                    $record['temprature'] ?? null,
                );
            } catch (\Throwable $th) {
                continue;
            }

            $violations = $this->validator->validate($dto);

            if (count($violations)) {
                continue;
            }

            $batch[] = $dto;
            $validMeasurementCount++;

            if (count($batch) === self::MAX_BATCH_SIZE) {
                $this->storeMeasurementBatch($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            $this->storeMeasurementBatch($batch);
        }

        unlink($path);

        if (!$validMeasurementCount) {
            throw new \Exception('Measurement File has no valid content!');
        }
    }

    /**
     * @param array<array-key, array<RawMeasurementDto>>
     */
    private function storeMeasurementBatch(array $batch): void
    {
        $this->measurementBatchStoreAction->execute(
            new MeasurementBatchStoreDto(
                measurementSetId: $this->measurementSetId,
                batch: new ArrayCollection($batch),
            ),
        );
    }
}
