<?php

namespace App\Data\Protocols\Auth;

use App\Domain\Models\Account;
use App\Domain\Models\JwtResponse;

interface LoginServiceInterface
{
    /**
     * @todo Receive Account and return JwtResponse
     */
    public function auth(Account $account): JwtResponse;
}
