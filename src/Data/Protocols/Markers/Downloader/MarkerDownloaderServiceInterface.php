<?php

namespace App\Data\Protocols\Markers\Downloader;

use App\Domain\Models\Marker;

interface MarkerDownloaderServiceInterface
{
    /**
     * Returns a stream of downloadable markers assets.
     *
     * @return resource
     */
    public function downloadMarkers(Marker ...$markers);
}
