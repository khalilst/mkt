<?php

namespace App\Module\Mkt\Dto;

use App\Dto\BaseDto;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class MeasurementBatchStoreDto extends BaseDto
{
    public function __construct(
        public readonly string $measurementSetId,

        /** @var ArrayCollection<RawMeasurementDto> $batch */
        public readonly ArrayCollection $batch,
    ) {}
}
