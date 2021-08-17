<?php

namespace App\Domain\Exceptions\Marker;

use App\Domain\Exceptions\Protocols\UniqueConstraintViolation\AbstractUniqueException;

class MarkerNameRepeated extends AbstractUniqueException
{
    protected string $responsaMessage = 'Marker name has already been utilized';

    public function addViolationQueue(string $message)
    {
        $this->responsaMessage = $message;
    }
}
