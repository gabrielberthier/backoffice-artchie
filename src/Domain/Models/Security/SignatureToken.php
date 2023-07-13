<?php

declare(strict_types=1);

namespace App\Domain\Models\Security;

use App\Domain\Models\Museum;
use DateTimeImmutable;
use DateTimeInterface;

use DateInterval;
use DateTime;
use JsonSerializable;

readonly class SignatureToken implements JsonSerializable
{
    public ?DateTimeInterface $createdAt;
    
    public ?DateTimeInterface $updated;
    
    public ?DateTimeInterface $ttl;
    
    public function __construct(
        public ?int $id,
        public string $signature,
        public string $privateKey,
        public ?Museum $museum,
        ?DateTimeInterface $createdAt,
        ?DateTimeInterface $updated,
        ?DateTimeInterface $ttl
    ) {
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
        $this->updated = $updated ?? new DateTimeImmutable();
        $this->ttl = $ttl ?? (new DateTime())->add(new DateInterval('P6M'));
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