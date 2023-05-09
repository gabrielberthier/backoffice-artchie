<?php

declare(strict_types=1);

namespace App\Domain\DTO;


final readonly class AccountDto
{
    public string $email;
    public string $username;
    public string $password;

    public function getData(): array
    {
        return [
            "email" => $this->email,
            "username" => $this->username,
            "password" => $this->password,
        ];
    }
}
