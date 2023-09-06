<?php

declare(strict_types=1);

namespace App\Domain\Models;

use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

readonly class Token implements JsonSerializable
{
    public function __construct(
        public string $email,
        public string $username,
        public string $role,
        public ?string $authType,
        public ?UuidInterface $uuid,
    ) {
    }

    /**
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return [
            'uuid' => $this->uuid,
            'email' => $this->email,
            'username' => $this->username,
            'role' => $this->role,
            'auth_type' => $this->authType,
        ];
    }
}