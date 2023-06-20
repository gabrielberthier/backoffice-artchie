<?php

declare(strict_types=1);

namespace App\Domain\Models\Security;

use App\Domain\Models\Museum;
use DateTimeInterface;

use DateInterval;
use DateTime;
use JsonSerializable;

readonly class SignatureToken implements JsonSerializable
{
    public function __construct(
        public ?int $id,
        public string $signature,
        public string $privateKey,
        public ?Museum $museum,
        public DateTimeInterface $createdAt = new DateTime(), 
        public DateTimeInterface $updated = new DateTime(), 
        public DateTimeInterface $ttl = (new DateTime())->add(new DateInterval('P6M')))
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'signature' => $this->signature,
            'privateKey' => $this->privateKey,
        ];
    }
}