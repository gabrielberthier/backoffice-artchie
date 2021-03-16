<?php
declare(strict_types=1);

namespace App\Domain\Exceptions;

use App\Domain\Exceptions\Protocols\DomainRecordNotFoundException;

class UserNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The user you requested does not exist.';
}
