<?php

namespace App\Domain\Models\Traits;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

trait UuidTrait
{
    /**
     * The internal primary identity key.
     *
     * @ORM\Column(type="uuid", unique=true)
     */
    private ?UuidInterface $uuid = null;

    /**
     * Generates a uuid before insert an item
     *
     * @return void
     * 
     * @ORM\PrePersist
     */
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
     *
     * @return self
     */
    public function setUuid(UuidInterface|string $uuid): self
    {
        $this->uuid = is_string($uuid) ? Uuid::fromString($uuid) : $uuid;

        return $this;
    }
}
