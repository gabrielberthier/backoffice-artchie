<?php

namespace Core\Providers\Contract;

use Core\Providers\AppProviderInterface;
use LogicException;

abstract class AbstractProvider implements AppProviderInterface
{
    protected string $target = "";

    public function __construct()
    {
        if ($this->target === "") {
            throw new LogicException(get_class($this) . ' must have a $target name');
        }
    }

    public function getTarget(): string
    {
        return $this->target;
    }
}