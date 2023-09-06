<?php

namespace App\Data\UseCases\Authentication;

use App\Domain\Dto\AccountDto;
use App\Domain\Dto\TokenLoginResponse;
use App\Domain\Factories\TokenResponseFactory;
use App\Domain\Repositories\AccountRepository;

class SocialLoginAuth
{

    public function __construct(
        private AccountRepository $accountRepository,
    ) {
    }

    public function authenticate(AccountDto $accountDto): TokenLoginResponse
    {
        $account = $this->accountRepository->findWithAuthType($accountDto->email, $accountDto->authType);

        if (is_null($account)) {
            $account = $this->accountRepository->insert($accountDto);
        }

        return TokenResponseFactory::createToken($account);
    }
}
