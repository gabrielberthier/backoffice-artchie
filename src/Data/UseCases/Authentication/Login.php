<?php

namespace App\Data\UseCases\Authentication;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Domain\Exceptions\NoAccountFoundException;
use App\Domain\Models\DTO\Credentials;
use App\Domain\Models\TokenLoginResponse;
use App\Domain\Repositories\AccountRepository;

class Login implements LoginServiceInterface
{
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }
    public function auth(Credentials $account): TokenLoginResponse
    {
        $account = $this->accountRepository->findByMail($account->getEmail());
        if (!$account) {
            throw new NoAccountFoundException();
        }
        return new TokenLoginResponse('', '');
    }
}
