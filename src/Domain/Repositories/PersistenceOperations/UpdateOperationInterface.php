<?php

namespace App\Domain\Repositories\PersistenceOperations;

use App\Domain\Contracts\ModelInterface;

interface UpdateOperationInterface
{
  public function update(int $id, array $values): ?ModelInterface;
}
