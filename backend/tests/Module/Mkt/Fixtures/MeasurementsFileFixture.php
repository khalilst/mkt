<?php

namespace App\Tests\Module\Mkt\Fixtures;

use App\Tests\Module\Mkt\Concerns\WithUploadFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenstruck\Foundry\Configuration as FoundryConfiguration;

final class MeasurementsFileFixture
{
    use WithUploadFile;

    public function generateFile(string $clientFileOriginalName, ?int $count = null): UploadedFile
    {
        $faker = FoundryConfiguration::instance()->faker;
        $count ??= $faker->numberBetween(100, 200);
        $rows = [];

        $now = new \DateTimeImmutable();

        for ($i = 0; $i < $count; $i++) {
            $now = $now->modify('+1 second');
            $time = $now->getTimestamp();
            $base = 25; // average temp
            $amp = 10;   // per minute fluctuation ±10°C
            $noise = mt_rand(-50, 50) / 100.0; // ±0.5°C noise
            $temp = $base + $amp * sin($i / 60 * 2 * M_PI) + $noise;
            $rows[] = [$time, round($temp, 2)];
        }

        $path = $this->createTempCsvFile(['measured_at', 'temprature'], $rows);

        return $this->createUploadedFile($clientFileOriginalName, $path);
    }
}
