<?php

declare(strict_types=1);

namespace App\Module\Mkt\Action;

use App\Module\Mkt\Dto\MeasurementSetStoreDto;
use App\Module\Mkt\Entity\MeasurementSet;
use App\Module\Mkt\Event\MeasurementSetCreatedEvent;
use App\Module\Mkt\Factory\MeasurementSetFactory;
use App\Module\Mkt\Repository\MeasurementSetRepository;
use App\Module\Mkt\ValueObject\MeasurementsFilePayload;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class MeasurementSetStoreAction
{
    public function __construct(
        private MeasurementSetFactory $factory,
        private MeasurementSetRepository $repository,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(MeasurementSetStoreDto $dto): MeasurementSet
    {
        $measurementSet = $this->factory->createFromStoreDto($dto);
        $this->repository->save($measurementSet);

        $this->eventDispatcher->dispatch(
            new MeasurementSetCreatedEvent(
                new MeasurementsFilePayload(
                    measurementSetId: $measurementSet->getId(),
                    measurementsFilePath: $this->storeMeasurementFile($dto->measurementsFile),
                    measurementsFileExtension: $dto->measurementsFile->getExtension(),
                ),
            ),
        );

        return $measurementSet;
    }

    private function storeMeasurementFile(UploadedFile $measurementFile): string
    {
        $directory = '/var/uploads';
        $filename = uniqid() . '-' . $measurementFile->getClientOriginalName();

        $measurementFile->move($directory, $filename);

        return "{$directory}/$filename";
    }
}
