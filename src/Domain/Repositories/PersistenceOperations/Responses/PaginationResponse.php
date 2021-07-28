<?php

namespace App\Domain\Repositories\PersistenceOperations\Responses;

use JsonSerializable;

class PaginationResponse implements JsonSerializable
{
    public function __construct(
        private int $total,
        private int $lastPage,
        private bool $currentHasNoResults,
        private array $items
    ) {
    }

    public function jsonSerialize()
    {
        return [
            'currentHasNoResults' => $this->currentHasNoResults,
            'total' => $this->total,
            'lastPage' => $this->lastPage,
            'items' => $this->items,
        ];
    }
}
