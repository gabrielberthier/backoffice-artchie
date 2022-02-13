<?php

namespace App\Domain\Repositories\PersistenceOperations\Responses;

final class PaginationResponse implements ResultSetInterface
{
    public function __construct(
        private int $total,
        private int $lastPage,
        private bool $currentHasNoResults,
        private array $items
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'currentHasNoResults' => $this->currentHasNoResults,
            'total' => $this->total,
            'lastPage' => $this->lastPage,
            'items' => $this->items,
        ];
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): array
    {
        $this->items = $items;

        return $this->items;
    }

    public function addItem($element): array
    {
        $this->items[] = $element;

        return $this->items;
    }

    public function count(): int
    {
        return $this->total;
    }
}
