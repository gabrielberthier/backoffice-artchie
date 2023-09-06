<?php

namespace App\Data\Entities\Cycle\Rbac;

use App\Data\Entities\Cycle\Traits\TimestampsTrait;
use App\Data\Entities\Cycle\Traits\UuidTrait;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\ORM\Entity\Behavior\{CreatedAt, UpdatedAt};
use Cycle\ORM\Entity\Behavior\Uuid\Uuid4;

#[Entity(table: 'cycle_resources')]
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
class CycleResource
{
    use TimestampsTrait, UuidTrait;
    #[Column(type: "primary")]
    private int $id;
    #[Column(type: 'string', nullable: false)]
    private string $name;
    #[Column(type: 'string', nullable: false, default: '')]
    private string $description;
    #[Column(type: 'bool', nullable: false)]
    private bool $isActive = true;

    /**
     * @var CyclePermission[] $extendedRoles
     */
    #[HasMany(target: CyclePermission::class)]
    private array $permissions;

    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function setPermissions(array $permissions): self
    {
        $this->permissions = $permissions;
        return $this;
    }

    public function addPermission(CyclePermission $permission): void
    {
        $this->permissions[] = $permission;
    }

    public function removePermission(CyclePermission $permission): void
    {
        $this->permissions = array_filter($this->permissions, static fn(CyclePermission $p) => $p !== $post);
    }
}