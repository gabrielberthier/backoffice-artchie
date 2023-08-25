<?php

declare(strict_types=1);

namespace App\Data\Entities\Cycle\Traits;

use Cycle\Annotated\Annotation\Column;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

trait UuidTrait
{
    #[Column(field: 'uuid', type: 'uuid')]
    private ?UuidInterface $uuid;

    public function generateUuid(): void
    {
        $this->uuid = Uuid::uuid4();
    }

    /**
     * Get the internal primary identity key.
     */
    public function getUuid(): ?UuidInterface
    {
        if ($this->uuid) {
            return $this->uuid;
        }

        return Uuid::uuid4();
    }

    public function setUuid(UuidInterface|string|null $uuid): self
    {
        $this->uuid = is_string($uuid) ? Uuid::fromString($uuid) : $uuid;

        return $this;
    }
}