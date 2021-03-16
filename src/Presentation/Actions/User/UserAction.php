<?php

declare(strict_types=1);

namespace App\Presentation\Actions\User;

use App\Presentation\Actions\Protocols\Action;
use App\Data\UseCases\User\UserService;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{

    /**
     * @param LoggerInterface $logger
     * @param UserService  $userRepository
     */
    public function __construct(protected LoggerInterface $logger, protected UserService $userService)
    {
    }
}
