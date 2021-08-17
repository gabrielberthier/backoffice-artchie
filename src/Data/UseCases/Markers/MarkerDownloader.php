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
            $markerPath = $marker->getAsset()->getPath();
            if ($this->verifyFileExistence($bucket, $markerPath)) {
                $resources[] = new ResourceObject($markerPath, $marker->getName());
                foreach ($marker->getResources() as $placementObject) {
                    if ($placementObject->getAsset()) {
                        $placementObjectPath = $placementObject->getAsset()->getPath();
                        if ($this->verifyFileExistence($bucket, $placementObjectPath)) {
                            $resources[] = new ResourceObject($placementObjectPath, $placementObject->getName());
                        }
                    }
                }
            }
        }

        return $this->zipper->zipObjects(
            $bucket,
            $resources
        );
    }

    /**
     * Verify if object exists in S3 buckets.
     */
    private function verifyFileExistence(string $bucket, string $object): bool
    {
        // https://docs.aws.amazon.com/aws-sdk-php/v3/guide/service/s3-stream-wrapper.html#other-object-functions
        $objectDir = 's3://'.$bucket.'/'.$object;

        return file_exists($objectDir) && is_file($objectDir);
    }
}
