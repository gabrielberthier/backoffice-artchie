<?php

declare(strict_types=1);

namespace App\Presentation\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class ViewUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    public function action(Request $request): Response
    {
        $userId = (int) $this->resolveArg('id');
        $user = $this->userService->findUserOfId($userId);

        $this->logger->info(sprintf('User of id `%d` was viewed.', $userId));

        return $this->respondWithData($user);
    }
}