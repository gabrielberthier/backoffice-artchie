<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Models\Museum;
use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;

interface MuseumRepository
{
    public function findByID(int $id): ?Museum;

    public function findByName(string $name): ?Museum;

    public function findByUUID(string $uuid): ?Museum;

    /**
     * Inserts a museum model.
     *
     * @throws MuseumAlreadyRegisteredException
     */
    public function add(Museum $model): bool;

    public function remove(int $museum): ?Museum;

    public function all(bool $paginate = false, $page = 1, $limit = 20): ResultSetInterface;
}
