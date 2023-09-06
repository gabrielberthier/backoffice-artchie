<?php

namespace App\Domain\Repositories\Pagination;

use App\Domain\Repositories\PersistenceOperations\Responses\PaginationResponse;

interface PaginationInterface
{
    public function paginate(
        int $page = 1,
        int $limit = 20
    ): PaginationResponse;
}
