<?php

namespace App\Domain\Repositories\PersistenceOperations;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Repositories\Pagination\PaginationInterface;

interface CrudOperations
{
    public function create(ModelInterface $model): bool;

    public function findAll(?PaginationInterface $pagination = null): array;

    public function update(int $id): ?ModelInterface;

    public function delete(int $id): ?ModelInterface;

    public function findByID(int $id): ?ModelInterface;

    public function findByKey(string $key, mixed $value): ?ModelInterface;
}
