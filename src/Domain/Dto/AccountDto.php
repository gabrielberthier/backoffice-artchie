<?php

declare(strict_types=1);

namespace App\Domain\Dto;


final readonly class AccountDto
{
    public function __construct(
        public string $email,
        public string $username,
        public string $password
    ) {

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