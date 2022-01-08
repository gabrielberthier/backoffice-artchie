<?php

namespace App\Infrastructure\Cryptography;

use App\Data\Protocols\Cryptography\HasherInterface;

class HashCreator implements HasherInterface
{
    public function hash(string $password, array $options = []): string
    {
        return manoucheHash($password);
    }
}
