<?php

namespace App\Tests\Module\Mkt\Concerns;

use League\Csv\Writer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\MimeTypes;

trait WithUploadFile
{
    private function createUploadedFile(string $clientFileOriginalName, string $path): UploadedFile
    {
        $mimeTypes = MimeTypes::getDefault();
        $guessedMimeTypes = $mimeTypes->getMimeTypes(pathinfo($clientFileOriginalName, PATHINFO_EXTENSION));
        $mimeType = $guessedMimeTypes[0] ?? 'application/octet-stream';

        return new UploadedFile($path, $clientFileOriginalName, 'application/octet-stream', test: true);
    }

    private function createTempCsvFile(array $header, array $rows): string
    {
        $path = tempnam(sys_get_temp_dir(), 'sftest');

        $this->createCsv($path, $header, $rows);

        return $path;
    }

    private function createTempFile(string $content): string
    {
        $path = tempnam(sys_get_temp_dir(), 'sftest');

        file_put_contents($path, $content);

        return $path;
    }

    private function createCsv(string $path, array $header, array $rows): string
    {
        $csv = Writer::from($path, 'w+');
        $csv->insertOne($header);
        $csv->insertAll($rows);

        return $path;
    }
}
