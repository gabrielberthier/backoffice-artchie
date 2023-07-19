<?php

namespace App\Domain\Repositories\PersistenceOperations;

use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;

interface ReadOperationInterface
{
  public function findAll(bool $paginate = false, int $page = 1, int $limit = 20): ResultSetInterface;

  public function findByID(int $id): ?object;

  public function findByKey(string $key, mixed $value): ?object;

  public function findItemsByKey(string $key, mixed $value): array;

  /**
   * Match conditions within repository
   *
   * @param array<string, mixed> $conditions
   */
  public function findWithConditions(array $conditions): array;
}
