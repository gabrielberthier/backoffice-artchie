<?php

use App\Domain\Repositories\AccountRepository;
use App\Infrastructure\Persistence\Account\DoctrineAccountRepository;

return [
    UserRepository::class => InMemoryUserRepository::class,
    AccountRepository::class => DoctrineAccountRepository::class,
];
