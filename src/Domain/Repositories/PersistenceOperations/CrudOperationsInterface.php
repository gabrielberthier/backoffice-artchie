<?php

namespace App\Domain\Repositories\PersistenceOperations;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Repositories\Pagination\PaginationInterface;

interface CrudOperationsInterface
{
    public function insert(ModelInterface $model): bool;

    public function findAll(?PaginationInterface $pagination = null): array;

    public function update(int $id, array $values): ?ModelInterface;

    public function delete(ModelInterface | int $id): ?ModelInterface;

    public function findByID(int $id): ?ModelInterface;

    public function findByKey(string $key, mixed $value): ?ModelInterface;
}
