<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class BaseApiTestCase extends ApiTestCase
{
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $entityManager = self::getContainer()->get('doctrine')->getManager();

        // Refresh Database
        (new ORMPurger($entityManager))->purge();

        $this->client = static::createClient();
    }
}
