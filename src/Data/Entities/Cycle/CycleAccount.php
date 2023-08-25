<?php

declare(strict_types=1);

namespace App\Data\Entities\Cycle;

use App\Data\Entities\Contracts\ModelCoercionInterface;
use App\Data\Entities\Cycle\Traits\TimestampsTrait;
use App\Data\Entities\Cycle\Traits\UuidTrait;
use App\Domain\Models\Account;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\ORM\Entity\Behavior;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid4;

#[Entity(table: 'cycle_accounts')]
#[Uuid4]
#[Behavior\CreatedAt(
    field: 'createdAt', // Required. By default 'createdAt'
    column: 'created_at' // Optional. By default 'null'. If not set, will be used information from property declaration.
)]
#[Behavior\UpdatedAt(
    field: 'updated', // Required. By default 'updatedAt' 
    column: 'updated_at' // Optional. By default 'null'. If not set, will be used information from property declaration.
)]
#[Index(columns: ['username'], unique: true)]
#[Index(columns: ['email'], unique: true)]
class CycleAccount implements ModelCoercionInterface
{
    use UuidTrait, TimestampsTrait;

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


    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function setAuthType(string $authAccountable): self
    {
        $this->authType = $authAccountable;

        return $this;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    public function toModel(): Account
    {
        return new Account(
            $this->id,
            $this->email,
            $this->username,
            $this->password,
            $this->authType,
            $this->uuid,
            $this->role,
            $this->createdAt,
            $this->updated
        );
    }
}