<?php

declare(strict_types=1);

namespace App\Domain\Models;

use App\Data\Protocols\Cryptography\TokenGeneratorInterface;
use App\Infrastructure\Cryptography\BodyTokenCreator;
use App\Infrastructure\Cryptography\CookieTokenCreator;
use JsonSerializable;

class TokenLoginResponse implements JsonSerializable
{
    private string $token;
    private string $renewToken;
    private TokenGeneratorInterface $tokenHandler;

    /**
     * @param Account                   $account
     * @param TokenGeneratorInterface[] $tokenizers
     */
    public function __construct(private Account $account)
    {
        $secretBody = $_ENV['JWT_SECRET'] ?? '';
        $secretCookie = $_ENV['JWT_SECRET_COOKIE'] ?? '';

        $this->tokenHandler = new CookieTokenCreator($this->account->getUuid());
        $this->renewToken = $this->tokenHandler->createToken($secretCookie);

        $this->tokenHandler = new BodyTokenCreator($this->account);
        $this->token = $this->tokenHandler->createToken($secretBody);
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getRenewToken()
    {
        return $this->renewToken;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'token' => $this->token,
            'renew-token' => $this->renewToken,
        ];
    }
}
