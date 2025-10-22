<?php

namespace App\Tests\Module\Mkt\Controller;

use App\Module\Mkt\Entity\Measurement;
use App\Module\Mkt\Factory\MeasurementFactory;
use App\Module\Mkt\Factory\MeasurementSetFactory;
use App\Tests\BaseApiTestCase;

final class MeasurementIndexControllerTest extends BaseApiTestCase
{
    public function testItReturnsValidMeasurementIndexResponse(): void
    {
        // Arrange
        $total = random_int(10, 20);
        $limit = 2;
        $pages = (int) ceil($total / $limit);
        $page = random_int(1, $pages);

        $measurementSet = MeasurementSetFactory::createOne()->_real();
        MeasurementFactory::createMany($total, [
            'measurement_set' => $measurementSet,
        ]);

        // Act
        $this->client->request(
            'GET',
            "/api/measurement-sets/{$measurementSet->getId()}/measurements?page={$page}&limit={$limit}",
        );

        // Assert
        self::assertResponseIsSuccessful();

        self::assertMatchesJsonSchema([
            'items' => [
                '*' => [
                    'id',
                    'measurement_set',
                    'measured_at',
                    'temperature',
                ],
            ],
            'total',
            'page',
            'limit',
            'pages',
        ], null, 'The response does not have a valid schema!');

        self::assertJsonContains(
            compact('total', 'page', 'limit', 'pages'),
            'The Response does not contain valid values!',
        );
    }

    public function testItReturnsValidMeasurements(): void
    {
        // Arrange
        $total = 2;

        $measurementSet = MeasurementSetFactory::createOne()->_real();

        /** @var array<Measurement> $measurements */
        $measurements = array_map(
            fn ($measurementProxy) => $measurementProxy->_real(),
            MeasurementFactory::createMany($total, ['measurement_set' => $measurementSet]),
        );

        usort(
            $measurements,
            fn (Measurement $a, Measurement $b) =>
                $a->getMeasuredAt()->getTimestamp() <=> $b->getMeasuredAt()->getTimestamp(),
        );

        // Act
        $this->client->request('GET', "/api/measurement-sets/{$measurementSet->getId()}/measurements");

        // Assert
        $items = array_map(
            fn (Measurement $measurement) => [
                'id' => $measurement->getId(),
                'measured_at' => $measurement->getMeasuredAt()->format('c'),
                'temperature' => $measurement->getTemperature(),
            ],
            $measurements,
        );

        self::assertJsonContains(compact('items'));
    }
}
