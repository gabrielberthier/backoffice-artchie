<?php

namespace App\Domain\Repositories\PersistenceOperations;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;

interface ReadOperationInterface
{
  public function findAll(bool $paginate = false, $page = 1, $limit = 20): ResultSetInterface;

  public function findByID(int $id): ?ModelInterface;

  public function findByKey(string $key, mixed $value): ?ModelInterface;

  public function findItemsByKey(string $key, mixed $value): array;

  /**
   * Match conditions within repository
   *
   * @param array<string, mixed> $conditions
   * @return array
   */
  public function findWithConditions(array $conditions): array;
}
