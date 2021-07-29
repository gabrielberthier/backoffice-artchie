<?php

namespace App\Data\Protocols\Markers\Downloader;

use App\Domain\Models\Marker;

interface MarkerDownloaderServiceInterface
{
    public function downloadMarkers(Marker $marker);
}
