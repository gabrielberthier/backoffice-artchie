<?php

namespace App\Data\Protocols\Cryptography;

interface ComparerInterface
{
    public function compare(string $password, string $hash): bool;
}
