<?php

namespace App\Data\UseCases\Markers;

use App\Data\Protocols\Markers\Downloader\MarkerDownloaderServiceInterface;
use App\Domain\Models\Marker;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MarkerRepositoryInterface;
use S3DataTransfer\Objects\ResourceObject;
use S3DataTransfer\S3\Zip\S3StreamObjectsZipDownloader;

class MarkerDownloader implements MarkerDownloaderServiceInterface
{
    public function __construct(
        private S3StreamObjectsZipDownloader $zipper,
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

        $markers = array_filter($markers, fn ($marker) => $marker->getIsActive());

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
            $resources[] = new ResourceObject($marker->getAsset()->getPath(), $marker->getName());
        }

        return $this->zipper->zipObjects(
            $bucket,
            $resources
        );
    }
}
