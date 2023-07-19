<?php

namespace App\Data\Entities\Contracts;

/**
 * @template T
 */
interface ModelCoercionInterface
{
    /**
     * @return T
     */
    public function toModel(): object;
}
