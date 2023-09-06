<?php

namespace App\Data\UseCases\Markers;

use App\Data\Protocols\Markers\Downloader\MarkerDownloaderServiceInterface;
use App\Data\Protocols\Media\MediaCollectorInterface;
use App\Domain\Dto\MediaResource;
use App\Domain\Models\Marker\Marker;
use App\Domain\Models\Museum;
use App\Domain\Repositories\MarkerRepositoryInterface;
use S3DataTransfer\Objects\ResourceObject;
use S3DataTransfer\S3\Zip\S3StreamObjectsZipDownloader;

class MarkerDownloader implements MarkerDownloaderServiceInterface
{
    private string $bucket = "artchier-markers";

    public function __construct(
        private S3StreamObjectsZipDownloader $zipper,
        private MarkerRepositoryInterface $repository,
        private MediaCollectorInterface $visitor
    ) {
    }

    /**
     * Returns all markers as a zip downloadable stream to be sent to the user's response.
     *
     * @return false|resource
     */
    public function downloadResourcesFromMuseum(int|Museum $id)
    {
        $markers = $this->repository->findAllByMuseum($id)->getItems();

        return $this->downloadMarkers($markers);
    }

    /**
     * Returns zip stream of object markers.
     *
     * @param Marker[] $markers
     *
     * @return false|resource
     */
    public function downloadMarkers(array $markers)
    {
        foreach ($markers as $marker) {
            $marker->accept($this->visitor);
        }

        $resources = array_filter(
            $this->visitor->collect(),
            fn(MediaResource $resource) => $this->verifyFileExistence(
                $resource->path
            )
        );

        $mappedArray = array_map(
            static fn(MediaResource $resource) => new ResourceObject(
                $resource->path,
                $resource->name
            ),
            $resources
        );

        return $this->zipper->zipObjects($this->bucket, $mappedArray);
    }

    /**
     * Verify if object exists in S3 buckets.
     */
    private function verifyFileExistence(string $object): bool
    {
        // https://docs.aws.amazon.com/aws-sdk-php/v3/guide/service/s3-stream-wrapper.html#other-object-functions
        $objectDir = "s3://" . $this->bucket . "/" . $object;

        return file_exists($objectDir) && is_file($objectDir);
    }
}