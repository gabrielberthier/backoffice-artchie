<?php

namespace App\Data\UseCases\Markers;

use App\Data\Protocols\Markers\Downloader\MarkerDownloaderServiceInterface;
use App\Domain\Models\Marker;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Infrastructure\Downloader\S3\ResourceObject;
use App\Infrastructure\Downloader\S3\S3StreamObjectsZipDownloader;
use App\Infrastructure\Downloader\S3\StreamResourceCollectorInterface;

class MarkerDownloader implements MarkerDownloaderServiceInterface
{
    public function __construct(
        private StreamResourceCollectorInterface $streamResourceCollectorInterface,
        private MarkerRepositoryInterface $repository
    ) {
    }

    /**
     * Returns all markers as a zip downloadable stream to be sent to the user's response.
     *
     * @return false|resource
     */
    public function downloadResourcesFromMuseum(int | Museum $id)
    {
        $markers = $this->repository->findAllByMuseum($id);

        return $this->downloadMarkers(...$markers);
    }

    /**
     * Returns zip stream of object markers.
     *
     * @param Marker ...$markers
     *
     * @return false|resource
     */
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
