<?php

declare(strict_types=1);

namespace App\Domain\Models;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="accounts")
 */
class Account implements JsonSerializable
{
    /*
    * @var \Ramsey\Uuid\UuidInterface
    *
    * @ORM\Id
    * @ORM\Column(type="uuid_binary")
    * @ORM\GeneratedValue(strategy="CUSTOM")
    * @ORM\CustomIdGenerator(class=UuidGenerator::class)
    */
    private ?UuidInterface $id;

    /**
     * @param string      $email
     * @param string      $username
     * @param string      $password
     * @param null|string $role
     */
    public function __construct(
        ?UuidInterface $id = null,
        private string $email,
        private string $username,
        private string $password,
        private ?string $role = 'common'
    ) {
        $this->id = $id;
    }

    public function getId(): UuidInterface | null
    {
        return $this->id;
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
            'id' => $this->id,
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
            'role' => $this->rolee,
        ];
    }
}
