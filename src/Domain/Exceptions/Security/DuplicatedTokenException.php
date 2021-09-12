<?php

namespace App\Domain\Exceptions\Security;

use App\Domain\Exceptions\Protocols\UniqueConstraintViolation\AbstractUniqueException;

class DuplicatedTokenException extends AbstractUniqueException
{
    protected string $responsaMessage = 'This museum has already produced a token';
}
