<?php

namespace App\Presentation\Actions\Auth\Utilities;

class CookieTokenManager
{
    public function implant(string $refreshToken)
    {
        setcookie(
            REFRESH_TOKEN,
            $refreshToken,
            $this->mountOptions()
        );
    }

    public function delete()
    {
        $options = $this->mountOptions();
        $options['expires'] = time() - 3600;
        setcookie(
            REFRESH_TOKEN,
            '',
            $options
        );
    }

    private function isProduction()
    {
        return 'PRODUCTION' === $_ENV['MODE'];
    }

    private function mountOptions(): array
    {
        $sameSite = $this->isProduction() ? 'None' : '';
        $secure = $this->isProduction();

        return [
            'expires' => time() + 31536000,
            'path' => '/',
            'httponly' => true,
            'samesite' => $sameSite,
            'secure' => $secure,
        ];
    }
}
