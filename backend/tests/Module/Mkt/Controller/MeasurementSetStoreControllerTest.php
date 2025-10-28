<?php

namespace App\Tests\Module\Mkt\Controller;

use App\Module\Mkt\Enum\MeasurementSetStatus;
use App\Module\Mkt\Repository\MeasurementRepository;
use App\Module\Mkt\Repository\MeasurementSetRepository;
use App\Tests\BaseApiTestCase;
use App\Tests\Module\Mkt\Concerns\WithUploadFile;
use App\Tests\Module\Mkt\Fixtures\MeasurementsFileFixture;
use App\Tests\Module\Mkt\Fixtures\MeasurementsFileWithInvalidMeasurementsFixture;
use App\Tests\Module\Mkt\Fixtures\StaticMeasurementsFileFixture;
use Closure;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class MeasurementSetStoreControllerTest extends BaseApiTestCase
{
    use WithUploadFile;

    public function testItStoresMeasurementSet(): void
    {
        // Arrange
        $measurementsCount = $this->faker->numberBetween(100, 300);
        $measurementsFile = (new MeasurementsFileFixture)->generateFile('test.csv', $measurementsCount);
        $title = $this->faker->text();

        // Act
        $this->requsetMeasurementsSetEndpoint($title, $measurementsFile);

        // Assert
        self::assertResponseStatusCodeSame(201);

        self::assertJsonContains([
            'title' => $title,
            'status' => MeasurementSetStatus::Completed->value,
        ], message: 'The Response does not contain valid title!');

        self::assertMatchesJsonSchema(
            ['created_at'],
            message: 'The Response should include the created_at attribute!'
        );

        self::assertMatchesJsonSchema(['id', 'mkt'], message: 'The Response should contain id!');
        $this->assertMeasurementsCount($measurementsCount);
        $this->assertMeasurementSetsCount();
    }

    #[TestWith(['sample1.csv', 24.5])]
    #[TestWith(['sample2.csv', 27.48])]
    #[TestWith(['sample3.csv', 23.1])]
    #[TestWith(['sample4.csv', 25.79])]
    #[TestWith(['sample5.csv', 5.99])]
    public function testItCalculateProperMkt(string $sampleName, $expectedMkt): void
    {
        // Assert
        $measurementsFile = (new StaticMeasurementsFileFixture)->generateFile($sampleName);
        $title = $this->faker->text();

        // Act
        $this->requsetMeasurementsSetEndpoint($title, $measurementsFile);

        // Assert
        self::assertResponseStatusCodeSame(201);
        self::assertMeasurementSetMkt($title, $expectedMkt);
    }

    #[TestWith(['invalid_sample1.csv'])]
    #[TestWith(['invalid_sample2.csv'])]
    public function testItHandlesMeasurementsFileWithInvalidContent(string $sampleName): void
    {
        // Assert
        $measurementsFile = (new StaticMeasurementsFileFixture)->generateFile($sampleName);
        $title = $this->faker->text();

        // Act
        $this->requsetMeasurementsSetEndpoint($title, $measurementsFile);

        // Assert
        self::assertResponseStatusCodeSame(201);
        self::assertMeasurementSetMkt(
            title: $title,
            expectedStatus: MeasurementSetStatus::Failed,
        );
    }

    public function testItExcludeInvalidMeasurementsFromMeasurementsFile(): void
    {
        // Arrange
        [
            'totalCount' => $totalCount,
            'invalidCount' => $invalidCount,
            'measurementsFile' => $measurementsFile,
        ] = (new MeasurementsFileWithInvalidMeasurementsFixture)->generateFile('test.csv');
        $title = $this->faker->text();

        // Act
        $this->requsetMeasurementsSetEndpoint($title, $measurementsFile);

        // Assert
        self::assertResponseStatusCodeSame(201);
        self::assertMeasurementsCount($totalCount - $invalidCount);
    }


    #[DataProvider('invalidDataProvider')]
    public function testItValidatesData(Closure $getData): void
    {
        // Arrange
        ['title' => $title, 'measurementsFile' => $measurementsFile] = $getData($this);

        // Arrange & Act
        $this->requsetMeasurementsSetEndpoint($title, $measurementsFile);

        // Assert
        self::assertResponseStatusCodeSame(422);
    }

    public static function invalidDataProvider(): Generator
    {
        yield 'Blank Title' => [
            fn() => ['title' => '', 'measurementsFile' => (new MeasurementsFileFixture)->generateFile('test.csv')],
        ];

        yield 'Long Title' => [
            fn(MeasurementSetStoreControllerTest $test) => [
                'title' => $test->faker->realText(300),
                'measurementsFile' => (new MeasurementsFileFixture)->generateFile('test.csv')
            ],
        ];

        yield 'Blank Measurement File' => [
            fn() => ['title' => 'Sample Title', 'measurementsFile' => null],
        ];

        yield 'Measurement File with invalid filetype' => [
            fn(MeasurementSetStoreControllerTest $test) => [
                'title' => 'Sample Title',
                'measurementsFile' => $test->createUploadedFile('sample.txt', $test->createTempFile('Some Random Data!')),
            ],
        ];
    }

    // Helpers

    private function assertMeasurementsCount(int $expectedCount): void
    {
        $count = $this->getContainer()
            ->get(MeasurementRepository::class)
            ->count();

        self::assertSame(
            $expectedCount,
            $count,
            "Expecting to have {$expectedCount} measurements but retrieved {$count} rows!",
        );
    }

    private function assertMeasurementSetsCount(): void
    {
        $count = $this->getContainer()
            ->get(MeasurementSetRepository::class)
            ->count();

        self::assertSame(
            1,
            $count,
            "Expecting to have 1 measurement set but retrieved {$count} rows!",
        );
    }

    private function assertMeasurementSetMkt(
        string $title,
        ?float $expectedMkt = null,
        MeasurementSetStatus $expectedStatus = MeasurementSetStatus::Completed,
    ): void {
        $measurementSet = $this->getContainer()
            ->get(MeasurementSetRepository::class)
            ->findOneBy(compact('title'), ['id' => 'DESC']);

        self::assertSame(
            $expectedMkt,
            $measurementSet->getMkt(),
            "Expecting to have {$expectedMkt} MKT but retrieved {$measurementSet->getMkt()}!",
        );

        self::assertSame(
            $expectedStatus->value,
            $measurementSet->getStatus()->value,
            "Expecting to have {$expectedStatus->label()} status but retrieved {$measurementSet->getStatus()->label()}!",
        );
    }

    private function requsetMeasurementsSetEndpoint(?string $title, ?UploadedFile $measurementsFile): void
    {
        $parameters = $title ? compact('title') : [];
        $files = $measurementsFile ? compact('measurementsFile') : [];

        $this->client->request(
            'POST',
            'api/measurement-sets',
            [
                'headers' => ['content-type' => 'multipart/form-data'],
                'extra' => compact('parameters', 'files'),
            ],
        );
    }
}
