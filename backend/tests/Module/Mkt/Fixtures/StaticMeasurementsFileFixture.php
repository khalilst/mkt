<?php

namespace App\Tests\Module\Mkt\Fixtures;

use App\Tests\Module\Mkt\Concerns\WithUploadFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class StaticMeasurementsFileFixture
{
    use WithUploadFile;

    public function generateFile(string $clientFileOriginalName): UploadedFile
    {
        $content = file_get_contents(__DIR__ . "/samples/{$clientFileOriginalName}");

        $path = $this->createTempFile($content);

        return $this->createUploadedFile($clientFileOriginalName, $path);
    }
}
