<?php

namespace App\Domain\Repositories\PersistenceOperations;



interface CrudOperationsInterface extends
    ReadOperationInterface,
    PersistOperationInterface,
    DeleteOperationInterface,
    UpdateOperationInterface
{
}
