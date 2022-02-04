<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\DTO\AccountDto;
use App\Domain\Exceptions\Account\UserAlreadyRegisteredException;
use App\Domain\Models\Account;

interface AccountRepository
{
    public function findByMail(string $mail): ?Account;

    public function findByAccess(string $access): ?Account;

    public function findByUUID(string $uuid): ?Account;

    /**
     * Inserts a user account.
     *
     * @throws UserAlreadyRegisteredException
     */
    public function insert(AccountDto $account): Account;
}
