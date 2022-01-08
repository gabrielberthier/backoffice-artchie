<?php

namespace App\Presentation\Actions\Markers\Utils;

use App\Domain\Models\Marker;
use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;

class PresignedUrlMapper
{
    public function __construct(private PresignedUrlCreator $presignedUrlCreator)
    {
    }

    public function mapResponse(ResultSetInterface $set)
    {
        foreach ($set->getItems() as $marker) {
            /** @var Marker */
            $marker = $marker;
            $markerAsset = $marker->getAsset();
            if ($markerAsset) {
                $presignedUrl = $this->presignedUrlCreator->setPresignedUrl($markerAsset);
                $markerAsset->setTemporaryLocation($presignedUrl);
            }
            foreach ($marker->getResources() as $resource) {
                $resourceAsset = $resource->getAsset();
                if ($resourceAsset) {
                    $presignedUrl = $this->presignedUrlCreator->setPresignedUrl($resourceAsset);
                    $resourceAsset->setTemporaryLocation($presignedUrl);
                }
            }
        }
    }
}
