<?php

namespace App\Domain\Factories;

use App\Domain\Dto\TokenLoginResponse;
use App\Domain\Models\Account;
use App\Infrastructure\Cryptography\BodyTokenCreator;
use App\Infrastructure\Cryptography\CookieTokenCreator;

class TokenResponseFactory
{
    public static function createToken(Account $account): TokenLoginResponse
    {
        $secretBody = $_ENV['JWT_SECRET'] ?? '';
        $secretCookie = $_ENV['JWT_SECRET_COOKIE'] ?? '';

        $cookieTokenHandler = new CookieTokenCreator($account->uuid);
        $renewToken = $cookieTokenHandler->createToken($secretCookie);

        $bodyTokenHandler = new BodyTokenCreator($account);
        $token = $bodyTokenHandler->createToken($secretBody);

        return new TokenLoginResponse($token, $renewToken);
    }
}
