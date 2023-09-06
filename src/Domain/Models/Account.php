<?php

declare(strict_types=1);

namespace App\Domain\Models;

use DateTimeImmutable;
use DateTimeInterface;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

readonly class Account implements JsonSerializable
{
    public function __construct(
        public ?int $id,
        public string $email,
        public string $username,
        public string $password,
        public ?string $authType,
        public ?UuidInterface $uuid = null,
        public ?string $role = 'common',
        public ?DateTimeInterface $createdAt = new DateTimeImmutable(), public ?DateTimeInterface $updated = new DateTimeImmutable())
    {
    }

    /**
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
            'role' => $this->role,
            'auth_type' => $this->authType,
            'created_at' => $this->createdAt,
            'updated' => $this->updated,
        ];
    }
}