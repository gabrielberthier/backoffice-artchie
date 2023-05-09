<?php

namespace App\Data\Protocols\Auth;

use App\Domain\Dto\Credentials;
use App\Domain\Dto\TokenLoginResponse;

interface LoginServiceInterface
{
    /**
     * Receives Account and return TokenLoginResponse.
     *
     * @param Credentials
     */
    public function auth(Credentials $credentials): TokenLoginResponse;
}