<?php

namespace App\Domain\DTO;

class Signature
{
    public function __construct(
        private string $privateKey,
        private string $publicKey,
        private string $signature
    ) {
    }

    public function privateKey()
    {
        return $this->privateKey;
    }

    public function publicKey()
    {
        return $this->publicKey;
    }

    public function signature()
    {
        return $this->signature;
    }
}
