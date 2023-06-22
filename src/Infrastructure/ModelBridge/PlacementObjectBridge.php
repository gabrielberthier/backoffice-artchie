<?php
namespace App\Infrastructure\ModelBridge;

use App\Data\Entities\Doctrine\DoctrineMuseum;
use App\Data\Entities\Doctrine\DoctrinePlacementObject;
use App\Data\Entities\Doctrine\DoctrinePosedAsset;
use App\Domain\Models\Museum;
use App\Domain\Models\PlacementObject\PlacementObject;

class PlacementObjectBridge
{
    public function convertFromModel(PlacementObject $resource): DoctrinePlacementObject
    {
        $doctrinePlacementObject = new DoctrinePlacementObject();
        $doctrinePlacementObject
            ->setCreatedAt($resource->createdAt)
            ->setId($resource->id)
            ->setIsActive($resource->isActive)
            ->setName($resource->name)
            ->setUpdated($resource->updated)
            ->setUuid($resource->uuid);

        if ($resource->asset) {
            $assetBridge = new AssetBridge();
            $doctrineAsset = $assetBridge->convertFromModel($resource->asset);
            $doctrinePosedAsset = new DoctrinePosedAsset($doctrinePlacementObject, $doctrineAsset);
            $doctrinePlacementObject->setAsset($doctrinePosedAsset);
        }
        if ($resource->marker) {
            $markerBridge = new MarkerBridge();
            $doctrineMarker = $markerBridge->convertFromModel($resource->marker);
            $doctrinePlacementObject->setMarker($doctrineMarker);
        }

        return $doctrinePlacementObject;
    }
    public function toModel(DoctrinePlacementObject $resource): PlacementObject
    {
        $assetBridge = new AssetBridge();
        $markerBridge = new MarkerBridge();
        $asset = is_null($resource->getAsset()->getAsset()) ? null : $assetBridge->toModel($resource->getAsset()->getAsset());
        
        return new PlacementObject(
            id: $resource->getId(),
            name: $resource->getName(),
            marker: $markerBridge->toModel($resource->getMarker()),
            isActive: $resource->getIsActive(),
            asset: $asset,
            createdAt: $resource->getCreatedAt(),
            updated: $resource->getUpdated(),
            uuid: $resource->getUuid(),
        );
    }
}