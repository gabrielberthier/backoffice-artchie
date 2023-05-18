<?php

declare(strict_types=1);

namespace App\Domain\Dto;

use App\Domain\Models\Enums\AuthTypes;


final readonly class AccountDto
{
    public function __construct(
        public string $email,
        public string $username,
        public string $password,
        public AuthTypes $authType = AuthTypes::CUSTOM
    ) {

    }

    public function getData(): array
    {
        return [
            "email" => $this->email,
            "username" => $this->username,
            "password" => $this->password,
            "authType" => $this->authType->value,
        ];
    }
}