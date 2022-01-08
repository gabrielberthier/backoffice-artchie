<?php

namespace App\Infrastructure\Cryptography;

use App\Data\Protocols\Cryptography\TokenGeneratorInterface;
use DateInterval;
use DateTime;
use Firebase\JWT\JWT;
use Ramsey\Uuid\UuidInterface;

class CookieTokenCreator implements TokenGeneratorInterface
{
    public function __construct(private UuidInterface $uuid)
    {
    }

    public function createToken(string $secret): string
    {
        $now = new DateTime();
        $future = new DateTime();
        $future->add(new DateInterval('P15D'));

        $jti = base64_encode(random_bytes(16)).$now->getTimeStamp();

        $payload = [
            'iat' => $now->getTimeStamp(),
            'exp' => $future->getTimeStamp(),
            'jti' => $jti,
            'sub' => $this->uuid,
            'iss' => 'ARTCHIE_COOKIE',
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }
}
