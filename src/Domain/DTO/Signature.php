<?php

namespace App\Domain\DTO;

final readonly class Signature
{
    public string $privateKey;
    public string $publicKey;
    public string $signature;
}
