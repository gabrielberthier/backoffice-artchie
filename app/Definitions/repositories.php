<?php

use App\Domain\Repositories\AccountRepository;
use App\Domain\Repositories\MuseumRepository;
use App\Domain\Repositories\UserRepository;
use App\Infrastructure\Persistence\Account\DoctrineAccountRepository;
use App\Infrastructure\Persistence\Museum\MuseumDoctrineRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;

return [
    UserRepository::class => InMemoryUserRepository::class,
    AccountRepository::class => DoctrineAccountRepository::class,
    MuseumRepository::class => MuseumDoctrineRepository::class,
];
