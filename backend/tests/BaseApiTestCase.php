<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Faker\Generator as FakerGenerator;
use Symfony\Component\Clock\Clock;
use Symfony\Component\Clock\MockClock;
use Zenstruck\Foundry\Configuration as FoundryConfiguration;

class BaseApiTestCase extends ApiTestCase
{
    protected Client $client;
    protected FakerGenerator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $entityManager = self::getContainer()->get('doctrine')->getManager();

        // Refresh Database
        (new ORMPurger($entityManager))->purge();

        $this->client = static::createClient();
        $this->faker = FoundryConfiguration::instance()->faker;
    }

    protected function freezeTime(): MockClock
    {
        $clock = new MockClock(new \DateTimeImmutable());
        Clock::set($clock); // freeze time globally

        return $clock;
    }
}
