<?php

namespace App\Data\UseCases\Authentication;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Data\Protocols\Cryptography\ComparerInterface;
use App\Data\UseCases\Authentication\Errors\IncorrectPasswordException;
use App\Domain\Exceptions\NoAccountFoundException;
use App\Domain\Models\DTO\Credentials;
use App\Domain\Models\TokenLoginResponse;
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
        $account = $this->accountRepository->findByAccess($credentials->getAccess());
        if ($account) {
            $passwordsMatch = $this->hashComparer->compare($credentials->getPassword(), $account->getPassword());
            if ($passwordsMatch) {
                return new TokenLoginResponse($account);
            }

            throw new IncorrectPasswordException();
        }

        throw new NoAccountFoundException();
    }
}
