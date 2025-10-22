<?php

namespace App\Module\Mkt\Query;

use App\Module\Mkt\Entity\Measurement;
use App\Module\Mkt\Repository\MeasurementRepository;
use App\Service\Paginator;
use Closure;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

final class MeasurementChunkQuery
{
    public function __construct(private MeasurementRepository $repository) {}

    /**
     * Return a chunk of measurements by MeasuredAt.
     */
    public function getChunkByMeasuredAt(int $measurementSetId, int $chunkSize, Closure $callback): void
    {
        /** @var DateTimeImmuatable|int $lastMeasuredAt */
        $lastMeasuredAt = 0;

        /** @var array<Measurement> $measurements */
        while ($measurements = $this->getChunkQuery($measurementSetId, $lastMeasuredAt, $chunkSize)->getResult()) {
            $callback(new ArrayCollection($measurements));

            $lastMeasuredAt = end($measurements)->getMeasuredAt();

            $this->repository->clearMemory();
        }
    }

    private function getChunkQuery(int $measurementSetId, DateTimeImmutable|int $lastMeasuredAt, int $chunkSize): Query
    {
        return $this->repository->createQueryBuilder('m')
            ->where('m.measurement_set = :measurementSetId')
            ->andWhere('m.measured_at > :lastMeasuredAt')
            ->setParameter('measurementSetId', $measurementSetId)
            ->setParameter('lastMeasuredAt', $lastMeasuredAt)
            ->orderBy('m.measured_at', 'ASC')
            ->setMaxResults($chunkSize)
            ->getQuery();
    }
}
