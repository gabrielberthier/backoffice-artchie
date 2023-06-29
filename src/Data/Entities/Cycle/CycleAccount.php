<?php

declare(strict_types=1);

namespace App\Data\Entities\Cycle;


use App\Data\Entities\Cycle\Traits\TimestampsTrait;
use App\Data\Entities\Cycle\Traits\UuidTrait;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\ORM\Entity\Behavior\UpdatedAt;
use Cycle\ORM\Entity\Behavior\Uuid\Listener\Uuid4;
use Cycle\ORM\Entity\Behavior\CreatedAt;
use JsonSerializable;

#[Entity(table: 'cycle_accounts')]
#[Uuid4]
#[CreatedAt(
    field: 'createdAt', // Required. By default 'createdAt'
    column: 'created_at' // Optional. By default 'null'. If not set, will be used information from property declaration.
)]
#[UpdatedAt(
    field: 'updated', // Required. By default 'updatedAt' 
    column: 'updated', // Optional. By default 'null'. If not set, will be used information from property declaration.
    nullable: true
)]
class CycleAccount implements JsonSerializable
{
    use TimestampsTrait;
    use UuidTrait;

    #[Column(type: "primary")]
    protected $id;

    #[Column(type: 'string', nullable: false)]
    private string $email;

    #[Column(type: 'string')]
    private string $username;

    #[Column(type: 'string')]
    private string $password;

    #[Column(type: 'string', nullable: true)]
    private ?string $role = 'common';

    #[Column(name: 'auth_type', type: 'string', nullable: true)]
    private ?string $authType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthType(): string
    {
        return $this->authType;
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
     * Set the value of username.
     *
     * @param mixed $username
     *
     * @return self
     */
    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set the value of password.
     *
     * @param mixed $password
     *
     * @return self
     */
    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set the value of role.
     *
     * @param mixed $role
     *
     * @return self
     */
    public function setRole($role): self
    {
        $this->role = $role;

        return $this;
    }

    public function setAuthType(string $authAccountable): self
    {
        $this->authType = $authAccountable;

        return $this;
    }

    /**
     * Set the value of email.
     *
     * @param mixed $email
     *
     * @return self
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
            'role' => $this->role,
            'auth_type' => $this->authType,
            'created_at' => $this->createdAt,
            'updated' => $this->updated,
        ];
    }
}
