<?php

declare(strict_types=1);

namespace App\Domain\DTO;

use JsonSerializable;

final readonly class Credentials implements JsonSerializable
{
    public string $access;
    public string $password;
    public string $role = '';

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
