<?php

use App\Domain\Repositories\AccountRepository;
use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Domain\Repositories\MuseumRepository;
use App\Domain\Repositories\SignatureTokenRepositoryInterface;
use App\Domain\Repositories\SignatureTokenRetrieverInterface;
use App\Domain\Repositories\UserRepository;

use App\Infrastructure\Persistence\Doctrine\DoctrineAccountRepository;
use App\Infrastructure\Persistence\Doctrine\MarkerDoctrineRepository;
use App\Infrastructure\Persistence\Doctrine\MuseumDoctrineRepository;
use App\Infrastructure\Persistence\Doctrine\SignatureTokenRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;

$repositoriesDefinition = static function (): array {
    if (boolval(getenv("RR"))) {
        return [
            UserRepository::class => InMemoryUserRepository::class,
            AccountRepository::class => DoctrineAccountRepository::class,
        ];
    }

    return [
        UserRepository::class => InMemoryUserRepository::class,
        AccountRepository::class => DoctrineAccountRepository::class,
        MuseumRepository::class => MuseumDoctrineRepository::class,
        MarkerRepositoryInterface::class => MarkerDoctrineRepository::class,
        SignatureTokenRepositoryInterface::class => SignatureTokenRepository::class,
        SignatureTokenRetrieverInterface::class => SignatureTokenRepository::class,
    ];
};

return $repositoriesDefinition();
