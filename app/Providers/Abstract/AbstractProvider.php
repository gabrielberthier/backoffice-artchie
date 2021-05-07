<?php

namespace Core\Providers\Abstract;

use Core\Providers\AppProviderInterface;
use LogicException;

abstract class AbstractProvider implements AppProviderInterface
{
    public function __construct()
    {
        if (!isset($this->target)) {
            throw new LogicException(get_class($this).' must have a $tablename');
        }
    }

    public function getTarget(): string
    {
        return $this->target;
    }
}
