<?php

namespace App\Data\Protocols\Auth;

use App\Domain\DTO\AccountDto;
use App\Domain\DTO\TokenLoginResponse;
use App\Domain\Models\Account;

interface SignUpServiceInterface
{
    /**
     * Receives Account and return TokenLoginResponse.
     *
     * @param Account
     */
    public function register(AccountDto $account): TokenLoginResponse;
}
