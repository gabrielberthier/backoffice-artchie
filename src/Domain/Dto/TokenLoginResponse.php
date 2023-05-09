<?php

declare(strict_types=1);

namespace App\Domain\Dto;

use App\Data\Protocols\Cryptography\TokenGeneratorInterface;
use App\Domain\Models\Account;
use App\Infrastructure\Cryptography\BodyTokenCreator;
use App\Infrastructure\Cryptography\CookieTokenCreator;
use JsonSerializable;

class TokenLoginResponse implements JsonSerializable
{
    public readonly string $token;
    public readonly string $renewToken;
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

    /**
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return [
            'token' => $this->token,
            'renew-token' => $this->renewToken,
        ];
    }
}