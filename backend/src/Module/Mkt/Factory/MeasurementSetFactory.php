<?php

namespace App\Module\Mkt\Factory;

use App\Module\Mkt\Dto\MeasurementSetStoreDto;
use App\Module\Mkt\Entity\MeasurementSet;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<MeasurementSet>
 */
final class MeasurementSetFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return MeasurementSet::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'title' => self::faker()->text(255),
            'mkt' => self::faker()->randomFloat(1, 100, 400),
            'created_at' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(MeasurementSet $measurementSet): void {})
        ;
    }

    public function createFromStoreDto(MeasurementSetStoreDto $dto): MeasurementSet
    {
        return (new MeasurementSet)
            ->setTitle($dto->title)
            ->setCreatedAt(new \DateTimeImmutable());
    }
}
