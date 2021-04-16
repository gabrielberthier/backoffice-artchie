<?php

use App\Domain\Repositories\AccountRepository;

return [
    UserRepository::class => (InMemoryUserRepository::class),
    AccountRepository::class => stdClass::class,
];
