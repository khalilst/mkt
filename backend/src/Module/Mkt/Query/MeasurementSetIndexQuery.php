<?php

namespace App\Module\Mkt\Query;

use App\Module\Mkt\Repository\MeasurementSetRepository;
use App\Service\Paginator;

final class MeasurementSetIndexQuery
{
    public function __construct(private MeasurementSetRepository $repository) {}

    /**
     * Return a paginated list of MeasurementSets.
     *
     * @param int $page
     * @param int|null $limit
     * @return PaginationResult
     */
    public function getPaginatedList(int $page = 1, ?int $limit = null): array
    {
        return Paginator::paginate(
            $this->repository->createQueryBuilder('s'),
            $page,
            $limit,
        );
    }
}
