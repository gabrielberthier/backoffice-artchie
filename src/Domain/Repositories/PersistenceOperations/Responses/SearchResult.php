<?php

namespace App\Domain\Repositories\PersistenceOperations\Responses;

class SearchResult implements ResultSetInterface
{
    public function __construct(private array $arrayItems)
    {
    }

    public function getItems(): array
    {
        return $this->arrayItems;
    }

    public function setItems(array $items): array
    {
        $this->arrayItems = $items;

        return $this->arrayItems;
    }

    public function addItem($element): array
    {
        $this->arrayItems[] = $element;

        return $this->arrayItems;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'total' => $this->count(),
            'items' => $this->arrayItems,
        ];
    }

    public function count(): int
    {
        return count($this->arrayItems);
    }
}
