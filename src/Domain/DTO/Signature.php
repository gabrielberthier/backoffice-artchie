<?php

namespace App\Domain\DTO;

class Signature
{
    public function __construct(private string $privateKey, private string $publicKey, private string $signature)
    {
    }
}
