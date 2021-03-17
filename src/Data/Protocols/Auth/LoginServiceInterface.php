<?php

namespace App\Data\Protocols\Auth;

use App\Domain\Models\DTO\Credentials;
use App\Domain\Models\TokenLoginResponse;

interface LoginServiceInterface
{
    /**
     * @todo Receive Account and return TokenLoginResponse
     */
    public function auth(Credentials $account): TokenLoginResponse;
}
