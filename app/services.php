<?php

declare(strict_types=1);

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Data\Protocols\User\UserUseCaseInterface;
use App\Data\UseCases\Authentication\Login;
use App\Data\UseCases\User\UserService;
use function DI\autowire;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserUseCaseInterface::class => autowire(UserService::class),
        LoginServiceInterface::class => autowire(Login::class),
    ]);
};
