<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Models\Marker;
use App\Domain\Models\Museum;
use App\Domain\Repositories\PersistenceOperations\CrudOperationsInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;

interface MarkerRepositoryInterface extends CrudOperationsInterface
{
    /**
     * @var Marker[]
     *
     * @param mixed $page
     * @param mixed $limit
     */
    public function findAllByMuseum(int|Museum $museum, bool $paginate = false, $page = 1, $limit = 20): ResultSetInterface;

    public function findByID(int $id): ?Marker;
}
