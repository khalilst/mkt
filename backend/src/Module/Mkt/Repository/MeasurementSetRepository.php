<?php

namespace App\Module\Mkt\Repository;

use App\Module\Mkt\Entity\MeasurementSet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
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

    public function save(MeasurementSet $measurementSet): void
    {
        $em = $this->getEntityManager();

        $em->persist($measurementSet);
        $em->flush();
    }
}
