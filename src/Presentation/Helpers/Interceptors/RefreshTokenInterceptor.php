<?php

namespace App\Presentation\Helpers\Interceptors;

use Psr\Http\Message\ServerRequestInterface;

class RefreshTokenInterceptor
{
    public function interceptRefreshToken(ServerRequestInterface $request): string
    {
        $cookies = $request->getCookieParams();
        $cookieName = REFRESH_TOKEN;
        $refreshToken = $cookies[$cookieName] ?? null;
        if (is_string($refreshToken)) {
            $request = $request->withAttribute($cookieName, $refreshToken);

            return $refreshToken;
        }

        return '';
    }
}
