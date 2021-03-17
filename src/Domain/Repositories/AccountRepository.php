<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Models\Account;

interface AccountRepository
{
    /**
     * @return Account
     */
    public function findByMail(string $mail): Account;
}
