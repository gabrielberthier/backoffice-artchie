<?php

namespace App\Domain\Dto;

final readonly class Signature
{
    public function __construct(
        public string $privateKey,
        public string $publicKey,
        public string $signature
    ) {
    }
}