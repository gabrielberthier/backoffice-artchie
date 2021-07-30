<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Models\Museum;
use App\Domain\Repositories\PersistenceOperations\CrudOperationsInterface;

interface MarkerRepositoryInterface extends CrudOperationsInterface
{
    /**
     * @var Marker[]
     */
    public function findAllByMuseum(int | Museum $museum): array;
}
