<?php

namespace App\Domain\Repositories\PersistenceOperations;


interface UpdateOperationInterface
{
  public function update(int $id, array $values): ?object;
}
