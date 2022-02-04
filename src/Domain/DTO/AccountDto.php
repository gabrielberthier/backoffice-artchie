<?php

declare(strict_types=1);

namespace App\Domain\DTO;


class AccountDto
{
    public function __construct(
        private string $email,
        private string $username,
        private string $password,
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

    public function getData(): array
    {
        return [
            "email" => $this->email,
            "username" => $this->username,
            "password" => $this->password,
        ];
    }
}
