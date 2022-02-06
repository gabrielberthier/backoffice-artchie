<?php

namespace App\Data\Protocols\Markers\Downloader;

use App\Domain\Models\Marker\Marker;
use App\Domain\Models\Museum;

interface MarkerDownloaderServiceInterface
{
    /**
     * @return resource
     */
    public function downloadResourcesFromMuseum(int|Museum $id);

    /**
     * Returns zip stream of object markers.
     *
     * @param Marker[] $markers
     *
     * @return false|resource
     */
    public function downloadMarkers(array $markers);
}
