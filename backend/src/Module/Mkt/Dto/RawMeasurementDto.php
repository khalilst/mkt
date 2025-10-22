<?php

namespace App\Module\Mkt\Dto;

use App\Dto\BaseDto;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

final class RawMeasurementDto extends BaseDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\DateTime]
        public readonly ?string $measuredAt,

        #[Assert\NotBlank]
        #[Assert\Type('float')]
        public readonly ?float $temperature,
    ) {}

    public static function fromRawData(string|int|null $measuredAt, ?float $temperature): self
    {

        return new self(
            measuredAt: is_numeric($measuredAt) && $measuredAt > 0
                ? (new DateTimeImmutable)->setTimestamp($measuredAt)->format('Y-m-d H:i:s')
                : $measuredAt,
            temperature: $temperature,
        );
    }
}
