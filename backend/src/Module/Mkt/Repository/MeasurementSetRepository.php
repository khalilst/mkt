<?php

namespace App\Module\Mkt\Repository;

use App\Module\Mkt\Entity\MeasurementSet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MeasurementSet>
 */
class MeasurementSetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeasurementSet::class);
    }
}
