<?php

namespace App\Data\Entities\Doctrine\Traits;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\Column;

trait TimestampsTrait
{
    #[Column(type: 'datetime', name: "created_at")]
    private ?DateTimeInterface $createdAt = null;

    #[Column(type: 'datetime', name: "updated_at")]
    private ?DateTimeInterface $updated = null;

    #[PreUpdate, PrePersist]
    public function updatedTimestamps(): void
    {
        $this->setUpdated(new DateTime('now'));
        if (is_null($this->getCreatedAt())) {
            $this->setCreatedAt(new DateTime('now'));
        }
    }

    public function setUpdated(?DateTimeInterface $dateTime): self
    {
        // WILL be saved in the database
        $this->updated = $dateTime;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    
    public function getUpdated()
    {
        return $this->updated;
    }
}