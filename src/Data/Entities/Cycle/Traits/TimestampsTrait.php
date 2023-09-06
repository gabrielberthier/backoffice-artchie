<?php

declare(strict_types=1);

namespace App\Data\Entities\Cycle\Traits;

use Cycle\Annotated\Annotation\Column;

trait TimestampsTrait
{

    #[Column(type: 'datetime')]
    private ?\DateTimeImmutable $createdAt = null;

    #[Column(type: 'datetime', nullable: true, name: 'updated_at')]
    private ?\DateTimeImmutable $updated = null;

    public function setUpdated(?\DateTimeImmutable $dateTime): self
    {
        // WILL be saved in the database
        $this->updated = $dateTime;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdated(): ?\DateTimeImmutable
    {
        return $this->updated;
    }
}