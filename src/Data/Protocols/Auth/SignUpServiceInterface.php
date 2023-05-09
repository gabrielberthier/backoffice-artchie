<?php

namespace App\Data\Protocols\Auth;

use App\Domain\Dto\AccountDto;
use App\Domain\Dto\TokenLoginResponse;

interface SignUpServiceInterface
{
    /**
     * Receives Account and return TokenLoginResponse.
     *
     * @param AccountDto
     */
    public function register(AccountDto $accountDto): TokenLoginResponse;
}