<?php

namespace App\Data\UseCases\Authentication;

use App\Data\Protocols\Auth\SignUpServiceInterface;
use App\Domain\DTO\AccountDto;
use App\Domain\DTO\TokenLoginResponse;
use App\Domain\Models\Account;
use App\Domain\Repositories\AccountRepository;
use Exception;

class SignUp implements SignUpServiceInterface
{
    public function __construct(private AccountRepository $accountRepository)
    {
    }

    public function register(AccountDto $account): TokenLoginResponse
    {
        return new TokenLoginResponse($this->accountRepository->insert($account));
    }
}
