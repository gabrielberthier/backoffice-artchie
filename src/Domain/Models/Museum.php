<?php

declare(strict_types=1);

namespace App\Domain\Models;

use App\Domain\Contracts\ModelInterface;
use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

readonly class Museum implements ModelInterface
{
    public function __construct(
        public ?int $id,
        public string $email,
        public string $name,
        public ?string $description = null,
        public ?string $info = null, 
        /** @var \App\Domain\Models\Marker\Marker[] */
        public array $markers = [],
        public ?UuidInterface $uuid = null,
        public ?DateTimeInterface $createdAt = new DateTimeImmutable(), 
        public ?DateTimeInterface $updated = new DateTimeImmutable()) 
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'email' => $this->email,
            'name' => $this->name,
            'info' => $this->info,
            'description' => $this->description,
            'created_at' => $this->createdAt,
            'updated' => $this->updated,
        ];
    }
}
