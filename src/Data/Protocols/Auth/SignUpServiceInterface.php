<?php

namespace App\Data\Protocols\Auth;

use App\Domain\Models\Account;
use App\Domain\Models\TokenLoginResponse;

interface SignUpServiceInterface
{
    /**
     * Receives Account and return TokenLoginResponse.
     *
     * @param Account
     */
    public function register(Account $account): TokenLoginResponse;
}
