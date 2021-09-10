<?php

namespace App\Data\Protocols\Auth;

use App\Domain\DTO\Credentials;
use App\Domain\DTO\TokenLoginResponse;

interface LoginServiceInterface
{
    /**
     * Receives Account and return TokenLoginResponse.
     *
     * @param Credentials
     */
    public function auth(Credentials $credentials): TokenLoginResponse;
}
