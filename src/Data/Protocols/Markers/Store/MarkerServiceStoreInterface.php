<?php

namespace App\Data\Protocols\Markers\Store;

use App\Domain\Models\Marker;

interface MarkerServiceStoreInterface
{
    public function insert(Marker $marker): void;
}
