<?php

namespace App\Data\Protocols\AsymCrypto;

use Ramsey\Uuid\UuidInterface;

interface SignerInterface
{
    public function sign(UuidInterface $uuidInterface): string;
}
