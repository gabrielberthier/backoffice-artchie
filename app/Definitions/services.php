<?php

declare(strict_types=1);

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Data\Protocols\Auth\SignUpServiceInterface;
use App\Data\Protocols\User\UserUseCaseInterface;
use App\Data\UseCases\Authentication\Login;
use App\Data\UseCases\Authentication\SignUp;
use App\Data\UseCases\User\UserService;

return [
    UserUseCaseInterface::class => UserService::class,
    LoginServiceInterface::class => Login::class,
    SignUpServiceInterface::class => SignUp::class,
];
