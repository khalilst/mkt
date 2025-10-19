<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

/**
 * @phpstan-type PaginationResult array{
 *     items: array<int, object>,
 *     total: int,
 *     page: int,
 *     limit: int,
 *     pages: int
 * }
 */
class Paginator
{

    const DEFAULT_LIMIT = 10;
    const MAX_LIMIT = 100;

    /**
     * Paginate entities.
     *
     * @param QueryBuilder $queryBuilder
     * @param int $page
     * @param int|null $limit
     * @return PaginationResult
     */
    public static function paginate(QueryBuilder $queryBuilder, int $page = 1, ?int $limit = null): array
    {
        $limit = $limit ?? static::DEFAULT_LIMIT;
        $limit = min($limit, static::MAX_LIMIT);
        $offset = ($page - 1) * $limit;

        // Set pagination in query
        $queryBuilder
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        // Get total results and paginated results
        $doctrinePaginator = new DoctrinePaginator($queryBuilder);
        $total = count($doctrinePaginator);

        return [
            'items' => iterator_to_array($doctrinePaginator),
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'pages' => ceil($total / $limit),
        ];
    }
}
