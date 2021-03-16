<?php

declare(strict_types=1);

namespace App\Presentation\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class ListUsersAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $users = $this->userService->findAll();

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($users);
    }
}
