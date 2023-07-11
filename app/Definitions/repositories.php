<?php

use App\Domain\Repositories\AccountRepository;
use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Domain\Repositories\MuseumRepository;
use App\Domain\Repositories\SignatureTokenRepositoryInterface;
use App\Domain\Repositories\SignatureTokenRetrieverInterface;
use App\Domain\Repositories\UserRepository;
use App\Infrastructure\Persistence\Doctrine\Account\DoctrineAccountRepository;
use App\Infrastructure\Persistence\Doctrine\Marker\MarkerDoctrineRepository;
use App\Infrastructure\Persistence\Doctrine\Museum\MuseumDoctrineRepository;
use App\Infrastructure\Persistence\Doctrine\SignatureToken\SignatureTokenRepository;
use App\Infrastructure\Persistence\Doctrine\User\InMemoryUserRepository;

return [
    UserRepository::class => InMemoryUserRepository::class,
    AccountRepository::class => DoctrineAccountRepository::class,
    MuseumRepository::class => MuseumDoctrineRepository::class,
    MarkerRepositoryInterface::class => MarkerDoctrineRepository::class,
    SignatureTokenRepositoryInterface::class => SignatureTokenRepository::class,
    SignatureTokenRetrieverInterface::class => SignatureTokenRepository::class,
];