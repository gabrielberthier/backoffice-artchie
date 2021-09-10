<?php

namespace App\Data\Protocols\AsymCrypto;

use App\Domain\DTO\Signature;

interface SignerInterface
{
    public function sign(): Signature;
}
