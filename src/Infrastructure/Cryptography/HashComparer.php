<?php

namespace App\Infrastructure\Cryptography;

use App\Data\Protocols\Cryptography\ComparerInterface;

class HashComparer implements ComparerInterface
{
    public function compare(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
