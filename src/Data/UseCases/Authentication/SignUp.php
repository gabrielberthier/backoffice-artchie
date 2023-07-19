<?php

namespace App\Data\UseCases\Authentication;

use App\Domain\Dto\AccountDto;
use App\Domain\Dto\TokenLoginResponse;
use App\Domain\Repositories\AccountRepository;
use App\Data\Protocols\Auth\SignUpServiceInterface;
use App\Domain\Factories\TokenResponseFactory;

class SignUp implements SignUpServiceInterface
{
    public function __construct(private AccountRepository $accountRepository)
    {
    }

    /**
     * Receives Account and returns TokenLoginResponse.
     *
     * @param AccountDto
     */
    public function register(AccountDto $accountDto): TokenLoginResponse
    {
        $account = $this->accountRepository->insert($accountDto);

        return TokenResponseFactory::createToken($account);
    }
}
