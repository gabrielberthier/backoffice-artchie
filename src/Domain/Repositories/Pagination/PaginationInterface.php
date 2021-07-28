<?php

namespace App\Domain\Repositories\Pagination;

use IteratorAggregate;

interface PaginationInterface
{
    public function paginate(
        int $page = 1,
        int $limit = 20
    ): IteratorAggregate;

    public function lastPage(): int;

    public function total(): int;

    public function currentPageHasNoResult(): bool;
}
