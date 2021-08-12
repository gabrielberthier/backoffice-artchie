<?php

namespace App\Domain\Models\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait TimestampsTrait
{
    /** @ORM\Column(type="datetime", name="created_at") */
    private ?DateTime $createdAt = null;

    /**
     * @ORM\Column(type="datetime", name="updated_at")
     */
    private ?DateTime $updated = null;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdated(new DateTime('now'));
        if (null === $this->getCreatedAt()) {
            $this->setCreatedAt(new DateTime('now'));
        }
    }

    public function setUpdated(DateTime $dateTime)
    {
        // WILL be saved in the database
        $this->updated = $dateTime;
    }

    /**
     * Get the value of createdAt.
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt.
     *
     * @param mixed $createdAt
     *
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updated.
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
