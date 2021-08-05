<?php

namespace App\Data\UseCases\Markers;

use App\Data\Protocols\Markers\Store\MarkerServiceStoreInterface;
use App\Domain\Models\Marker;

class MarkerServiceStore implements MarkerServiceStoreInterface
{
    public function insert(Marker $marker): void
    {
    }
}
