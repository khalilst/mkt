<?php

namespace App\Tests\Module\Mkt\Fixtures;

use App\Tests\Module\Mkt\Concerns\WithUploadFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class MeasurementsFileWithMktFixture
{
    use WithUploadFile;

    /**
     * @return array{uploadedFile: UploadedFile, mkt: float}
     */
    public function generateFile(string $clientFileOriginalName, ?int $count = null): array
    {
        $rows = [];

        $now = new \DateTimeImmutable();

        for ($i = 0; $i < 10; $i++) {
            $now = $now->modify('+1 second');
            $time = $now->getTimestamp();
            $temp = $i + 1;
            $rows[] = [$time, round($temp, 2)];
        }

        $path = $this->createTempCsvFile(['measured_at', 'temprature'], $rows);

        return [
            'uploadedFile' => $this->createUploadedFile($clientFileOriginalName, $path),
            'mkt' => 5.99,
        ];
    }
}
