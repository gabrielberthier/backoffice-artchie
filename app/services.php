<?php
declare(strict_types=1);

use App\Data\Protocols\User\UserUseCaseInterface;
use App\Data\UseCases\User\UserService;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserUseCaseInterface::class => \DI\autowire(UserService::class),
    ]);
};
