<?php

namespace App\Data\UseCases\Authentication;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Data\Protocols\Cryptography\ComparerInterface;
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
        $account = $this->accountRepository->findByMail($credentials->getEmail());
        if (!$account) {
            throw new NoAccountFoundException();
        }
        $this->hashComparer->compare($credentials->getPassword(), $account->getPassword());
        return new TokenLoginResponse('', '');
    }
}
