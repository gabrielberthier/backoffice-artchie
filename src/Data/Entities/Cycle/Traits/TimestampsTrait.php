<?php

declare(strict_types=1);

namespace App\Data\Entities\Cycle\Traits;

use Cycle\Annotated\Annotation\Column;

trait TimestampsTrait
{

    #[Column(type: 'datetime')]
    private ?\DateTimeImmutable  $createdAt = null;

    #[Column(type: 'datetime', nullable: true)]
    private ?\DateTimeImmutable  $updated = null;
}
