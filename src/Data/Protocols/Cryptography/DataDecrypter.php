<?php

namespace App\Data\Protocols\Cryptography;

interface DataDecrypter
{
    public function decrypt(string $subject): string;
}
