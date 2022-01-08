<?php

namespace App\Data\Protocols\Markers\Store;

use App\Domain\Models\Marker\Marker;

interface MarkerServiceStoreInterface
{
    public function insert(int $museumId, Marker $marker): Marker;
}
