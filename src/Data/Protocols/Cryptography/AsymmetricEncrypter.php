<?php

namespace App\Data\Protocols\Cryptography;

use App\Domain\Dto\Signature;

interface AsymmetricEncrypter
{
    public function encrypt(string $json_data): Signature;
}