<?php

namespace App\Data\Protocols\PersistenceOperations;

use App\Domain\Contracts\ModelInterface;

interface CrudOperations
{
    public function create(ModelInterface $model): bool;

    public function findAll(?PaginationInterface $pagination = null): array;

    public function update(int $id): ?ModelInterface;

    public function delete(int $id): ?ModelInterface;

    public function findByID(int $id): ?ModelInterface;

    public function findByKey(string $key, mixed $value): ?ModelInterface;
}
