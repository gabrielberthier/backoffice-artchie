<?php

declare(strict_types=1);

namespace App\Domain\Models;

use JsonSerializable;
use \Ramsey\Uuid\UuidInterface;


class JwtResponse implements JsonSerializable
{

    /**
     * @param string $token
     * @param string|null $renewToken
     */
    public function __construct(
        private string $token,
        private ?string $renewToken
    ) {
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
