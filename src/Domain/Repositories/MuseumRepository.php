<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Exceptions\Museum\MuseumAlreadyRegisteredException;
use App\Domain\Models\Museum;

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
    public function insert(ModelInterface $model): bool;

    public function add(Museum $model): bool;

    public function delete(ModelInterface | int $museum): ?Museum;
}
