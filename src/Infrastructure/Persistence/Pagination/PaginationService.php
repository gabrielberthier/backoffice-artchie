<?php

namespace App\Infrastructure\Persistence\Pagination;

use App\Domain\Repositories\Pagination\PaginationInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\PaginationResponse;
use ArrayIterator;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginationService implements PaginationInterface
{
    private Paginator $paginator;

    public function __construct(Query | QueryBuilder $query, $fetchJoinCollection = true)
    {
        $this->paginator = new Paginator($query, $fetchJoinCollection);
    }

    public function paginate(
        int $page = 1,
        int $limit = 20
    ): PaginationResponse {
        $currentPage = intval($page) ?: 1;
        $limit = intval($limit);
        $query = $this->paginator
            ->getQuery()
            ->setFirstResult($limit * ($currentPage - 1))
            ->setMaxResults($limit)
        ;

        $this->paginator = new Paginator($query);

        $arrayItems = iterator_to_array($this->paginator->getIterator());

        return new PaginationResponse($this->total(), $this->lastPage(), $this->currentPageHasNoResult(), $arrayItems);
    }

    public function lastPage(): int
    {
        $paginator = $this->paginator;

        return ceil($paginator->count() / $paginator->getQuery()->getMaxResults());
    }

    public function total(): int
    {
        return $this->paginator->count();
    }

    public function currentPageHasNoResult(): bool
    {
        /**
         * @var ArrayIterator
         */
        $iterator = $this->paginator->getIterator();

        return !($iterator->count());
    }
}
