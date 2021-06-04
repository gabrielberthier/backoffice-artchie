<?php

namespace App\Data\UseCases\Authentication;

use App\Data\Protocols\Auth\SignUpServiceInterface;
use App\Domain\Models\Account;
use App\Domain\Models\TokenLoginResponse;
use App\Domain\Repositories\AccountRepository;
use DomainException;

class SignUp implements SignUpServiceInterface
{
    public function __construct(private AccountRepository $accountRepository)
    {
    }

    public function register(Account $account): TokenLoginResponse
    {
        $result = $this->accountRepository->insert($account);
        if ($result) {
            return new TokenLoginResponse($account);
        }

        throw new DomainException("One error occured while saving user's data");
    }
}
