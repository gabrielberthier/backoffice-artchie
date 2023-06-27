<?php
namespace App\Infrastructure\ModelBridge;

use App\Data\Entities\Doctrine\DoctrineMarker;
use App\Data\Entities\Doctrine\DoctrineMarkerAsset;
use App\Data\Entities\Doctrine\DoctrinePlacementObject;
use App\Domain\Models\Marker\Marker;

class MarkerBridge
{
    public function convertFromModel(Marker $marker): DoctrineMarker
    {
        $doctrineMarker = new DoctrineMarker();
        $asset = $marker->asset;
        # Basic info
        $doctrineMarker->setName($marker->name);
        $doctrineMarker->setText($marker->text);
        $doctrineMarker->setTitle($marker->title);

        if ($asset) {
            $assetBridge = new AssetBridge();
            $doctrineAsset = $assetBridge->convertFromModel($asset);
            $doctrineMarkerAsset = new DoctrineMarkerAsset($doctrineMarker, $doctrineAsset);
            $doctrineMarker->setAsset($doctrineMarkerAsset);
        }

        $doctrineMarker->setId($marker->id);
        $doctrineMarker->setCreatedAt($marker->createdAt);
        $doctrineMarker->setUpdated($marker->updated);
        $doctrineMarker->setUuid($marker->uuid);


        if ($marker->museum) {
            $museumBridge = new MuseumBridge();
            $doctrineMarker->setMuseum($museumBridge->convertFromModel($marker->museum));
        }

        $placementObjectBridge = new PlacementObjectBridge();

        foreach ($marker->resources as $resource) {
            $placementObjectDoctrine = $placementObjectBridge->convertFromModel($resource);
            $doctrineMarker->addResource($placementObjectDoctrine);
        }

        return $doctrineMarker;
    }
    public function toModel(DoctrineMarker $doctrineMarker): Marker
    {
        $placementObjectBridge = new PlacementObjectBridge();
        $resources = $doctrineMarker->getResources()->map(
            fn(DoctrinePlacementObject $el) => $placementObjectBridge->toModel($el)
        );
        $assetBridge = new AssetBridge();
        $asset = is_null($doctrineMarker->getAsset()->getAsset()) ? null : $assetBridge->toModel($doctrineMarker->getAsset()->getAsset());

        return new Marker(
            id: $doctrineMarker->getId(),
            museum: $doctrineMarker->getMuseum(),
            name: $doctrineMarker->getName(),
            text: $doctrineMarker->getText(),
            title: $doctrineMarker->getTitle(),
            asset: $asset,
            isActive: $doctrineMarker->getIsActive(),
            resources: $resources,
            createdAt: $doctrineMarker->getCreatedAt(),
            updated: $doctrineMarker->getUpdated(),
            uuid: $doctrineMarker->getUuid(),
        );

    }
}