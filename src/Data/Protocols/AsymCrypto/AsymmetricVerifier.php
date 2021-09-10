<?php

namespace App\Data\Protocols\AsymCrypto;

interface AsymmetricVerifier
{
    public function verify(): bool;
}
