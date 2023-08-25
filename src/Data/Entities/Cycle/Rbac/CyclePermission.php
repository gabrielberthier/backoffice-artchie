<?php

namespace App\Data\Entities\Cycle\Rbac;

use App\Data\Entities\Cycle\Traits\TimestampsTrait;
use App\Data\Entities\Cycle\Traits\UuidTrait;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
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

    /**
     * @return string
     */
    public function getContext(): string
    {
        return $this->context;
    }

    /**
     * @param string $context 
     * @return self
     */
    public function setContext(string $context): self
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name 
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id 
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
}