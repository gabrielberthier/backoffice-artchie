<?php

use App\Data\Protocols\Markers\Downloader\MarkerDownloaderServiceInterface;
use App\Domain\Models\Marker;
use App\Infrastructure\Downloader\S3\ResourceObject;
use App\Infrastructure\Downloader\S3\S3StreamObjectsZipDownloader;
use App\Infrastructure\Downloader\S3\StreamResourceCollectorInterface;

class MarkerDownloader implements MarkerDownloaderServiceInterface
{
    public function __construct(private StreamResourceCollectorInterface $streamResourceCollectorInterface)
    {
    }

    public function downloadMarkers(Marker ...$markers)
    {
        $bucket = 'artchier-markers';
        /**
         * @var ResourceObject[]
         */
        $resources = [];

        foreach ($markers as $marker) {
            $resources[] = new ResourceObject($marker->getUrl(), $marker->getName());
        }

        $zipper = new S3StreamObjectsZipDownloader($this->streamResourceCollectorInterface);

        return $zipper->zipObjects(
            $bucket,
            $resources
        );
    }
}
