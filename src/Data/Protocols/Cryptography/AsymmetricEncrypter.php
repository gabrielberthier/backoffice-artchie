<?php

namespace App\Data\Protocols\Cryptography;

use App\Domain\DTO\Signature;

interface AsymmetricEncrypter
{
    public function encrypt(string $json_data): Signature;
}
