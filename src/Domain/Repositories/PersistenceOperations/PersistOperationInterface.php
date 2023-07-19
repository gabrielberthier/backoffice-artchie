<?php

namespace App\Domain\Repositories\PersistenceOperations;

use App\Domain\Contracts\ModelInterface;

interface PersistOperationInterface
{
  public function insert(ModelInterface $model): void;
}
