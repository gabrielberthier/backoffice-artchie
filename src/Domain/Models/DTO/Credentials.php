<?php

declare(strict_types=1);

namespace App\Domain\Models\DTO;

use JsonSerializable;

class Credentials implements JsonSerializable
{
    private string $email;
    private string $username;

    public function __construct(
        private string $access,
        private string $password,
        private string $role = ''
    ) {
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function getAccess()
    {
        return $this->access;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'access' => $this->getAccess(),
            'password' => $this->password,
            'role' => $this->role,
        ];
    }
}
