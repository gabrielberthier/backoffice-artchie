<?php

declare(strict_types=1);

namespace App\Domain\Models;

use DateTime;
use Firebase\JWT\JWT;
use JsonSerializable;

class TokenLoginResponse implements JsonSerializable
{
    private string $token;
    private ?string $renewToken;

    public function __construct(
        private Account $account
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

    private function createToken()
    {
        $now = new DateTime();
        $future = new DateTime('now +15 minutes');

        $jti = base64_encode(random_bytes(16));

        $payload = [
            'iat' => $now->getTimeStamp(),
            'exp' => $future->getTimeStamp(),
            'jti' => $jti,
            'sub' => $this->account->getUsername(),
            'data' => $this->createData(),
            'iss' => 'ARTCHIE',
        ];

        $secret = getenv('JWT_SECRET');
        $token = JWT::encode($payload, $secret, 'HS256');

        $data['token'] = $token;
        $data['expires'] = $future->getTimeStamp();
    }

    private function createData(): array
    {
        $email = $this->account->getEmail();
        $uuid = $this->account->getId();
        $role = $this->account->getRole();
        $username = $this->account->getUsername();

        return [
            'email' => $email,
            'uuid' => $uuid,
            'role' => $role,
            'username' => $username,
        ];
    }
}
