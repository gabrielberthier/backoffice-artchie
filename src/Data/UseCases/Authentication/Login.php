<?php

namespace App\Data\UseCases\Authentication;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Data\Protocols\Cryptography\ComparerInterface;
use App\Data\UseCases\Authentication\Errors\IncorrectPasswordException;
use App\Domain\Dto\Credentials;
use App\Domain\Dto\TokenLoginResponse;
use App\Domain\Exceptions\NoAccountFoundException;
use App\Domain\Factories\TokenResponseFactory;
use App\Domain\Repositories\AccountRepository;

class Login implements LoginServiceInterface
{
    public function __construct(
        private AccountRepository $accountRepository,
        private ComparerInterface $hashComparer
    ) {
    }

    public function auth(Credentials $credentials): TokenLoginResponse
    {
        $account = $this->accountRepository->findByAccess($credentials->access);
        if ($account instanceof \App\Domain\Models\Account) {
            $passwordsMatch = $this->hashComparer->compare($credentials->password, $account->password);
            if ($passwordsMatch) {
                return TokenResponseFactory::createToken($account);
            }

            throw new IncorrectPasswordException();
        }

        throw new NoAccountFoundException();
    }
}
