<?php

declare(strict_types=1);

namespace App\Presentation\Actions\User;

use App\Data\Protocols\User\UserUseCaseInterface;
use App\Presentation\Actions\Protocols\Action;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{

    public function __construct(protected LoggerInterface $logger, protected UserUseCaseInterface $userService)
    {
    }
}