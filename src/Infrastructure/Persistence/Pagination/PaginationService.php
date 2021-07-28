<?php

namespace App\Infrastructure\Persistence\Pagination;

use App\Domain\Repositories\Pagination\PaginationInterface;
use ArrayIterator;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use JsonSerializable;

class PaginationService implements PaginationInterface, JsonSerializable
{
    private Paginator $paginator;

    public function __construct(Query | QueryBuilder $query, $fetchJoinCollection = true)
    {
        $this->paginator = new Paginator($query, $fetchJoinCollection);
    }

    public function paginate(
        int $page = 1,
        int $limit = 20
    ): Paginator {
        $currentPage = $page ?: 1;
        $query = $this->paginator
            ->getQuery()
            ->setFirstResult($limit * ($currentPage - 1))
            ->setMaxResults($limit)
        ;

        $this->paginator = new Paginator($query);

        return $this->paginator;
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

    public function jsonSerialize()
    {
        return [
            'currentHasNoResults' => $this->currentPageHasNoResult(),
            'total' => $this->total(),
            'lastPage' => $this->lastPage(),
            'items' => $this->paginator->getQuery()->getResult(),
        ];
    }
}
