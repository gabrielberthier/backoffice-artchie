<?php

namespace App\Infrastructure\Persistence\Doctrine\Pagination;

use App\Domain\Repositories\Pagination\PaginationInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\PaginationResponse;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginationService implements PaginationInterface
{
    public function __construct(private Query|QueryBuilder $query, private bool $fetchJoinCollection = true)
    {
    }

    public function paginate(
        int $page = 1,
        int $limit = 20
    ): PaginationResponse {
        $currentPage = $page ?: 1;

        $paginator = new Paginator(
            $this->query,
            $this->fetchJoinCollection
        );

        $query = $paginator
            ->getQuery()
            ->setFirstResult($limit * ($currentPage - 1))
            ->setMaxResults($limit);

        $paginator = new Paginator($query);

        $arrayItems = iterator_to_array($paginator->getIterator());

        return new PaginationResponse(
            $paginator->count(),
            $this->lastPage($paginator),
            $this->currentPageHasNoResult($paginator),
            $arrayItems
        );
    }

    private function lastPage(Paginator $paginator): int
    {
        return ceil($paginator->count() / $paginator->getQuery()->getMaxResults());
    }

    private function currentPageHasNoResult(Paginator $paginator): bool
    {
        $iterator = $paginator->getIterator();

        return !(iterator_count($iterator));
    }
}
