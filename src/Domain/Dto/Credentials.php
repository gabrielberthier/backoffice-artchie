<?php

declare(strict_types=1);

namespace App\Domain\Dto;

use JsonSerializable;

final readonly class Credentials implements JsonSerializable
{
    public function __construct(
        public string $access,
        public string $password,
        public string $role = '',
    ) {

    }

    /**
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return [
            'access' => $this->access,
            'password' => $this->password,
            'role' => $this->role,
        ];
    }
}