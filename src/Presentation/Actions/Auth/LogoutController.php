<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Auth;

use App\Presentation\Actions\Auth\Utilities\CookieTokenManager;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;

class LogoutController extends Action
{
    public function action(): Response
    {
        $cookieManager = new CookieTokenManager();

        $cookieManager->delete();

        return $this->respondWithData(['message' => 'You have been unlogged'])->withStatus(200, 'Unlogged');
    }
}
