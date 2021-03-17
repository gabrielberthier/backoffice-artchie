<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use App\Domain\Exceptions\Protocols\DomainRecordNotFoundException;

class NoAccountFoundException extends DomainRecordNotFoundException
{
    public $message = 'The account you requested does not exist.';
}
