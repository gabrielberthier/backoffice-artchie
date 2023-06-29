<?php

declare(strict_types=1);

namespace App\Data\Entities\Cycle\Traits;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid4;
use Ramsey\Uuid\UuidInterface;

trait UuidTrait
{

    #[Column(field: 'uuid', type: 'uuid')]
    private UuidInterface $uuid;
}
