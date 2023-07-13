<?php

namespace App\Data\Entities\Doctrine\Traits;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\Column;

trait UuidTrait
{
    #[Column(type: 'uuid', unique:true)]
    private ?UuidInterface $uuid = null;

    #[PrePersist]
    public function generateUuid(): void
    {
        $this->uuid = Uuid::uuid4();
    }

    /**
     * Get the internal primary identity key.
     */
    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    /**
     * Set the internal primary identity key.
     *
     * @param UuidInterface|string $uuid
     */
    public function setUuid(UuidInterface|string|null $uuid): self
    {
        $this->uuid = is_string($uuid) ? Uuid::fromString($uuid) : $uuid;

        return $this;
    }
}
