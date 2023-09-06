<?php

namespace App\Data\Entities\Cycle\Rbac;

use App\Data\Entities\Cycle\Traits\TimestampsTrait;
use App\Data\Entities\Cycle\Traits\UuidTrait;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\ORM\Entity\Behavior\{CreatedAt, UpdatedAt};
use Cycle\ORM\Entity\Behavior\Uuid\Uuid4;

#[Entity(table: 'cycle_permissions')]
#[Uuid4]
#[CreatedAt(
    field: 'createdAt',
    column: 'created_at'
)]
#[UpdatedAt(
    field: 'updated',
    column: 'updated_at'
)]
#[Index(columns: ['name'], unique: true)]
class CyclePermission
{

    use TimestampsTrait, UuidTrait;
    #[Column(type: "primary")]
    private int $id;
    #[Column(type: 'string', nullable: false)]
    private string $name;
    #[Column(type: 'string', nullable: false)]
    private string $context;
    #[BelongsTo(target: CycleRole::class)]
    private CycleRole $role;

    #[BelongsTo(target: CycleResource::class)]
    private CycleResource $resource;

    public function getContext(): string
    {
        return $this->context;
    }

    public function setContext(string $context): self
    {
        $this->context = $context;

        return $this;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getRole(): CycleRole
    {
        return $this->role;
    }

    public function setRole(CycleRole $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getResource(): CycleResource
    {
        return $this->resource;
    }

    public function setResource(CycleResource $resource): self
    {
        $this->resource = $resource;
        return $this;
    }
}