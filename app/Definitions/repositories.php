<?php

use App\Domain\Repositories\AccountRepository;
use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Domain\Repositories\MuseumRepository;
use App\Domain\Repositories\SignatureTokenRepositoryInterface;
use App\Domain\Repositories\SignatureTokenRetrieverInterface;
use App\Domain\Repositories\UserRepository;
use App\Infrastructure\Persistence\Account\DoctrineAccountRepository;
use App\Infrastructure\Persistence\Marker\MarkerDoctrineRepository;
use App\Infrastructure\Persistence\Museum\MuseumDoctrineRepository;
use App\Infrastructure\Persistence\SignatureToken\SignatureTokenRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;

return [
    UserRepository::class => InMemoryUserRepository::class,
    AccountRepository::class => DoctrineAccountRepository::class,
    MuseumRepository::class => MuseumDoctrineRepository::class,
    MarkerRepositoryInterface::class => MarkerDoctrineRepository::class,
    SignatureTokenRepositoryInterface::class => SignatureTokenRepository::class,
    SignatureTokenRetrieverInterface::class => SignatureTokenRepository::class,
];
