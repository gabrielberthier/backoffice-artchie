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
     * Returns a stream of downloadable markers assets.
     *
     * @return resource
     */
    public function downloadMarkers(Marker ...$markers);
}
