<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Auth;

use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;

class LogoutController extends Action
{
    public function action(): Response
    {
        setcookie(
            name: REFRESH_TOKEN,
            value: '',
            expires_or_options: time() - 3600,
            path: '/',
            httponly: true
        );

        return $this->respondWithData(['message' => 'You have been unlogged'])->withStatus(200, 'Unlogged');
    }
}
