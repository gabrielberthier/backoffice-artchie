<?php

namespace App\Infrastructure\Cryptography;

use App\Data\Protocols\Cryptography\TokenGeneratorInterface;
use App\Domain\Models\Account;
use DateTime;
use Firebase\JWT\JWT;

class BodyTokenCreator implements TokenGeneratorInterface
{
    public function __construct(private Account $account)
    {
    }

    public function createToken(string $secret): string
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

        return JWT::encode($payload, $secret, 'HS256');
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
