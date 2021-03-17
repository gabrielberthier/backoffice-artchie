<?php

namespace App\Data\Protocols\Auth;

use App\Domain\Models\Account;
use App\Domain\Models\TokenLoginResponse;

interface LoginServiceInterface
{
    /**
     * @todo Receive Account and return TokenLoginResponse
     */
    public function auth(Account $account): TokenLoginResponse;
}
