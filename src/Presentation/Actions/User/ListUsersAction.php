<?php

declare(strict_types=1);

namespace App\Presentation\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ListUsersAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    public function action(Request $request): Response
    {
        $users = $this->userService->findAll();

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($users);
    }
}
