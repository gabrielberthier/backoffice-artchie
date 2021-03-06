<?php

namespace App\Domain\Repositories\PersistenceOperations;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\PaginationResponse;

interface CrudOperationsInterface
{
    public function insert(ModelInterface $model): bool;

    public function findAll(bool $paginate = false, $page = 1, $limit = 20): array | PaginationResponse;

    public function update(int $id, array $values): ?ModelInterface;

    public function delete(ModelInterface | int $id): ?ModelInterface;

    public function findByID(int $id): ?ModelInterface;

    public function findByKey(string $key, mixed $value): ?ModelInterface;
}
