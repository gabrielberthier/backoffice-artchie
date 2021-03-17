<?php

namespace App\Data\UseCases\Authentication;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Domain\Models\Account;
use App\Domain\Models\TokenLoginResponse;
use App\Domain\Repositories\AccountRepository;

class Login implements LoginServiceInterface
{
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }
    public function auth(Account $account): TokenLoginResponse
    {
        $this->accountRepository->findByMail($account->getEmail());
        return new TokenLoginResponse('', '');
    }
}
