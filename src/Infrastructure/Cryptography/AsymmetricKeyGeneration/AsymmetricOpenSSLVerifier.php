<?php

namespace App\Infrastructure\Cryptography\AsymmetricKeyGeneration;

use App\Data\Protocols\AsymCrypto\AsymmetricVerifier;

class AsymmetricOpenSSLVerifier implements AsymmetricVerifier
{
    public function verify(string $signature): bool
    {
        return true;
    }
}