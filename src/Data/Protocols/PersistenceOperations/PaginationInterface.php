<?php

namespace App\Data\Protocols\PersistenceOperations;

use IteratorAggregate;

interface PaginationInterface
{
    public function paginate($query, int $page, int $limit): IteratorAggregate;

    public function lastPage(): int;

    public function total(): int;

    public function currentPageHasNoResult(): bool;
}
