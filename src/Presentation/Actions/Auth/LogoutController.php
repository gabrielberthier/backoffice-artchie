<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Auth;

use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;

class LogoutController extends Action
{
    public function action(): Response
    {
        $sameSite = 'PRODUCTION' === $_ENV['MODE'] ? 'None' : '';

        setcookie(
            REFRESH_TOKEN,
            '',
            [
                'expires' => time() - 3600,
                'path' => '/',
                'httponly' => true,
                'samesite' => $sameSite,
                'secure' => true,
            ]
        );

        return $this->respondWithData(['message' => 'You have been unlogged'])->withStatus(200, 'Unlogged');
    }
}
