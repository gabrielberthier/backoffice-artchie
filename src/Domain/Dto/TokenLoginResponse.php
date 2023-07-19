<?php

declare(strict_types=1);

namespace App\Domain\Dto;

use JsonSerializable;

readonly class TokenLoginResponse implements JsonSerializable
{

    public function __construct(public string $token, public string $renewToken)
    {
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
