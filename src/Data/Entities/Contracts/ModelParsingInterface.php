<?php

namespace App\Data\Entities\Contracts;

/**
 * @template T
 */
interface ModelParsingInterface
{
    /**
     * @param T
     */
    public function fromModel(object $model): static;
}
