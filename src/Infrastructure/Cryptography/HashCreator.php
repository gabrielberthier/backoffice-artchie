<?php

namespace App\Infrastructure\Cryptography;

use App\Data\Protocols\Cryptography\HasherInterface;
use function Core\functions\manoucheHash;

class HashCreator implements HasherInterface
{
    public function hash(string $password, array $options = []): string
    {
        return manoucheHash($password);
    }
}