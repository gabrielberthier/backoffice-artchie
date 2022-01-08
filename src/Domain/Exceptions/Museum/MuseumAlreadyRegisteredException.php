<?php

namespace App\Domain\Exceptions\Museum;

use App\Domain\Exceptions\Protocols\UniqueConstraintViolation\AbstractUniqueException;

class MuseumAlreadyRegisteredException extends AbstractUniqueException
{
    protected string $responsaMessage = 'Museum name is already taken';
}
