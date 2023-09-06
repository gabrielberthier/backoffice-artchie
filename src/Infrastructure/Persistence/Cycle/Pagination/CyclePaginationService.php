<?php

namespace App\Infrastructure\Persistence\Cycle\Pagination;


use App\Domain\Repositories\Pagination\PaginationInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\PaginationResponse;
use Cycle\Database\Query\SelectQuery;
use Cycle\ORM\Select;
use Spiral\Pagination\Paginator;

class CyclePaginationService implements PaginationInterface
{
    public function __construct(private Select|SelectQuery $select)
    {
    }

    public function paginate(
        int $page = 1,
        int $limit = 20
    ): PaginationResponse {
        $paginator = new Paginator($limit);

        $currentPage = $page ?: 1;

        $selectCounter = clone $this->select;

        $count = $selectCounter->count();

        $paginator->withPage($currentPage)->paginate($this->select);

        $arrayItems = $this->select->fetchAll();

        return new PaginationResponse(
            $count,
            (int) ceil($count / $limit),
            !(iterator_count($arrayItems)),
            $arrayItems
        );
    }
}