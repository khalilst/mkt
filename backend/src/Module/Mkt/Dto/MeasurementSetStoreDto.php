<?php

namespace App\Module\Mkt\Dto;

use App\Dto\BaseDto;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class MeasurementSetStoreDto extends BaseDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public readonly ?string $title = null,

        #[Assert\NotNull]
        #[Assert\File(
            mimeTypes: ['text/csv'],
            mimeTypesMessage: 'Please upload a valid CSV file.).'
        )]
        public readonly ?UploadedFile $measurementsFile = null,
    ) {}

    public static function fromRequest(Request $request) {
        return new self(
            title: $request->request->get('title'),
            measurementsFile: $request->files->get('measurementsFile'),
        );
    }
}
