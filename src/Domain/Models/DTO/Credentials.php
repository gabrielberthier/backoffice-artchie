<?php

declare(strict_types=1);

namespace App\Domain\Models\DTO;

use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class Credentials implements JsonSerializable
{
    /**
     * @param null|UuidInterface $id
     * @param string             $email
     * @param string             $username
     * @param string             $password
     * @param null|string        $role
     */
    public function __construct(
        private string $email,
        private string $username,
        private string $password,
        private string $role = ''
    ) {
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id ?? '',
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
            'role' => $this->role,
        ];
    }
}
