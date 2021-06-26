<?php

namespace App\Data\Protocols\Cryptography;

interface DataEncrypter
{
    public function encrypt(string $cyphed): string;
}
