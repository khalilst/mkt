<?php

namespace App\Tests\Module\Mkt\Fixtures;

use App\Tests\Module\Mkt\Concerns\WithUploadFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenstruck\Foundry\Configuration as FoundryConfiguration;

final class MeasurementsFileWithInvalidMeasurementsFixture
{
    use WithUploadFile;

    /**
     * @return array{totalCount: int, invalidCount: int, measurementsFile: UploadedFile}
     */
    public function generateFile(string $clientFileOriginalName, ?int $count = null): array
    {
        $faker = FoundryConfiguration::instance()->faker;
        $count ??= $faker->numberBetween(100, 200);
        $invalidCount = $faker->numberBetween(10, 50);
        $rows = [];

        $now = new \DateTimeImmutable();

        // Seed valid data
        for ($i = 0; $i < $count; $i++) {
            $now = $now->modify('+1 second');
            $time = $now->getTimestamp();
            $base = 25; // average temp
            $amp = 10;   // per minute fluctuation ±10°C
            $noise = mt_rand(-50, 50) / 100.0; // ±0.5°C noise
            $temp = $base + $amp * sin($i / 60 * 2 * M_PI) + $noise;
            $rows[] = [$time, round($temp, 2)];
        }

        // Seed invalid data
        for ($i = 0; $i < $invalidCount; $i++) {
            $rows[] = match($faker->numberBetween(1, 4)) {
                1 => ['', 25],
                2 => [$faker->text(10), 25],
                3 => [$now->getTimestamp(), ''],
                4 => [$now->getTimestamp(), $faker->text(10)],
            };
        }

        $path = $this->createTempCsvFile(['measured_at', 'temprature'], $rows);

        return [
            'totalCount' => $count + $invalidCount,
            'invalidCount' => $invalidCount,
            'measurementsFile' => $this->createUploadedFile($clientFileOriginalName, $path)
        ];
    }
}
