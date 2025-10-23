<?php

namespace App\DataFixtures;

use App\Module\Mkt\Factory\MeasurementFactory;
use App\Module\Mkt\Factory\MeasurementSetFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class MeasurementsFixture extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['init'];
    }

    public function load(ObjectManager $manager): void
    {
        $sets = MeasurementSetFactory::createMany(15);
        $manager->flush();
        $manager->clear();

        foreach ($sets as $set) {
            MeasurementFactory::createMany(rand(50, 150), ['measurement_set' => $set]);
            $manager->flush();
            $manager->clear();
        }
    }
}
