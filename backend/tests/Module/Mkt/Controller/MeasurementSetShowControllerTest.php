<?php

namespace App\Tests\Module\Mkt\Controller;

use App\Module\Mkt\Entity\MeasurementSet;
use App\Module\Mkt\Factory\MeasurementSetFactory;
use App\Tests\BaseApiTestCase;
use PHPUnit\Framework\Attributes\TestWith;

final class MeasurementSetShowControllerTest extends BaseApiTestCase
{
    public function testItReturnsValidResponse(): void
    {
        // Arrange
        /** @var MeasurementSet $measurementSet */
        $measurementSet = MeasurementSetFactory::createOne()->_real();

        // Act
        $this->client->request(
            'GET',
            "/api/measurement-sets/{$measurementSet->getId()}",
        );

        // Assert
        self::assertResponseIsSuccessful();

        self::assertJsonContains(
            [
                'id' => $measurementSet->getId(),
                'title' => $measurementSet->getTitle(),
                'mkt' => $measurementSet->getMkt(),
                'created_at' => $measurementSet->getCreatedAt()->format('c'),
            ],
            'The Response does not contain valid values!',
        );
    }

    #[TestWith([-1])]
    #[TestWith([PHP_INT_MAX])]
    #[TestWith(['xyz'])]
    public function testItReturnsNotFoundForInvalidMeasurementSet(mixed $id): void
    {
        // Arrange & Act
        $this->client->request(
            'GET',
            "/api/measurement-sets/{$id}",
        );

        // Assert
        self::assertResponseStatusCodeSame(404);
    }
}
