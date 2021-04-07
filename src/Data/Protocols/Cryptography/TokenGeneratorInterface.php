<?php

namespace App\Data\Protocols\Cryptography;

interface TokenGeneratorInterface
{
    public function createToken(string $secret): string;
}
