<?php

namespace App\Domain\Repositories\PersistenceOperations;

use App\Domain\Contracts\ModelInterface;

interface DeleteOperationInterface
{
  public function delete(ModelInterface|int $id): ?object;
}
