<?php

namespace App\Module\Mkt\Query;

use App\Module\Mkt\Repository\MeasurementRepository;
use App\Service\Paginator;

final class MeasurementIndexQuery
{
    public function __construct(private MeasurementRepository $repository) {}

    /**
     * Return a paginated list of Measurements.
     *
     * @param int $measurementSetId
     * @param int $page
     * @param int|null $limit
     * @return PaginationResult
     */
    public function getPaginatedList(int $measurementSetId, int $page = 1, ?int $limit = null): array
    {
        return Paginator::paginate(
            $this->repository->createQueryBuilder('m')
                ->where('m.measurement_set = :measurementSetId')
                ->setParameter('measurementSetId', $measurementSetId),
            $page,
            $limit,
        );
    }
}
