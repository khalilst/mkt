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

    public function save(MeasurementSet $measurementSet): void
    {
        $em = $this->getEntityManager();

        $em->persist($measurementSet);
        $em->flush();
    }

    public function updateMkt(MeasurementSet $measurementSet): int
    {
        return $this->createQueryBuilder('s')
            // Set the value of the column
            ->update()
            ->set('s.mkt', ':mkt')
            ->setParameter('mkt', $measurementSet->getMkt())

            // Filter by the primary key
            ->where('s.id = :measurementSetId')
            ->setParameter('measurementSetId', $measurementSet->getId())

            // Execute the query
            ->getQuery()
            ->execute(); // Returns the number of affected rows
    }
}
