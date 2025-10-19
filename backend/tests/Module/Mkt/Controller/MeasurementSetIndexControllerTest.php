<?php

namespace App\Tests\Module\Mkt\Controller;

use App\Module\Mkt\Entity\MeasurementSet;
use App\Module\Mkt\Factory\MeasurementSetFactory;
use App\Tests\BaseApiTestCase;

final class MeasurementSetIndexControllerTest extends BaseApiTestCase
{
    public function testItReturnsValidResponse(): void
    {
        // Arrange
        $total = random_int(10, 20);
        $limit = 2;
        $pages = (int) ceil($total / $limit);
        $page = random_int(1, $pages);

        MeasurementSetFactory::createMany($total);

        // Act
        $this->client->request(
            'GET',
            "/api/measurement-sets?page={$page}&limit={$limit}",
        );

        // Assert
        self::assertResponseIsSuccessful();

        self::assertMatchesJsonSchema([
            'items' => [
                '*' => [
                    'id',
                    'title',
                    'mkt',
                    'created_at',
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

    public function testItReturnsValidMeasurementSets(): void
    {
        // Arrange
        $total = 2;

        /** @var array<MeasurementSet> $measurementSets */
        $measurementSets = array_map(
            fn ($measurementSetProxy) => $measurementSetProxy->_real(),
            MeasurementSetFactory::createMany($total),
        );

        // Act
        $this->client->request('GET', '/api/measurement-sets');

        // Assert
        $items = array_map(
            fn (MeasurementSet $measurementSet) => [
                'id' => $measurementSet->getId(),
                'title' => $measurementSet->getTitle(),
                'mkt' => $measurementSet->getMkt(),
                'created_at' => $measurementSet->getCreatedAt()->format('c'),
            ],
            $measurementSets,
        );

        self::assertJsonContains(
            compact('items'),
        );
    }
}
