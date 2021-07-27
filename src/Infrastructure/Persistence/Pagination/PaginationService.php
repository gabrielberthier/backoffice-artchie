<?php

namespace App\Infrastructure\Persistence\Pagination;

use App\Domain\Repositories\Pagination\PaginationInterface;
use ArrayIterator;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginationService implements PaginationInterface
{
    public function __construct(private Paginator $paginator)
    {
    }

    /**
     * @param Query|QueryBuilder $query
     * @param Request            $request
     */
    public function paginate($query, int $page, int $limit): Paginator
    {
        $currentPage = $page ?: 1;
        $paginator = new Paginator($query);
        $paginator
            ->getQuery()
            ->setFirstResult($limit * ($currentPage - 1))
            ->setMaxResults($limit)
        ;

        return $paginator;
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
