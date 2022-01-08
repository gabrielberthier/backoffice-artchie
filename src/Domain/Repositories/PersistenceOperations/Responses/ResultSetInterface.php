<?php

namespace App\Domain\Repositories\PersistenceOperations\Responses;

use JsonSerializable;

interface ResultSetInterface extends JsonSerializable
{
    public function getItems(): array;

    public function setItems(array $items): array;

    public function addItem($element): array;

    public function count(): int;
}
