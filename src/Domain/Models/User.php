<?php

declare(strict_types=1);

namespace App\Domain\Models;

use JsonSerializable;

class User implements JsonSerializable
{
    public readonly ?int $id;
    
    public readonly string $username;
    
    public readonly string $firstName;
    
    public readonly string $lastName;

    public function __construct(?int $id, string $username, string $firstName, string $lastName)
    {
        $this->id = $id;
        $this->username = strtolower($username);
        $this->firstName = ucfirst($firstName);
        $this->lastName = ucfirst($lastName);
    }

   
    /**
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];
    }
}
