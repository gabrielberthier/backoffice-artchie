<?php
namespace App\Infrastructure\ModelBridge;

use App\Data\Entities\Doctrine\DoctrineAccount;
use App\Domain\Models\Account;



class AccountBridge
{
    public function convertFromModel(Account $account): DoctrineAccount
    {
        $input = \Core\functions\arrayToCamelCase($account->jsonSerialize());

        return new DoctrineAccount(...$input);
    }

    public function toModel(DoctrineAccount $doctrineAccount): Account
    {
        $input = \Core\functions\arrayToCamelCase($doctrineAccount->jsonSerialize());

        return new Account(...$input);
    }
}