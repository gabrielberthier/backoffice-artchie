<?php

namespace App\Presentation\Actions\Markers\Utils;

use App\Domain\Models\Marker\Marker;
use App\Domain\Repositories\PersistenceOperations\Responses\ResultSetInterface;

class PresignedUrlMapper
{
    public function __construct(private PresignedUrlCreator $presignedUrlCreator)
    {
    }

    public function mapResponse(ResultSetInterface $set)
    {
        foreach ($set->getItems() as $markerElement) {
            $marker = $markerElement;
            $markerAsset = $marker->assetInformation();
            if ($markerAsset instanceof \App\Domain\Models\Assets\AbstractAsset) {
                $presignedUrl = $this->presignedUrlCreator->setPresignedUrl($markerAsset);
                $markerAsset->setTemporaryLocation($presignedUrl);
            }
            
            foreach ($marker->getResources() as $resource) {
                $resourceAsset = $resource->assetInformation();
                if ($resourceAsset) {
                    $presignedUrl = $this->presignedUrlCreator->setPresignedUrl($resourceAsset);
                    $resourceAsset->setTemporaryLocation($presignedUrl);
                }
            }
        }
    }
}