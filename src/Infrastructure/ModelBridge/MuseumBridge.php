<?php
namespace App\Infrastructure\ModelBridge;

use App\Data\Entities\Doctrine\DoctrineMarker;
use App\Data\Entities\Doctrine\DoctrineMuseum;
use App\Domain\Models\Museum;

class MuseumBridge
{
    public function convertFromModel(Museum $museum): DoctrineMuseum
    {
        $doctrineMuseum = new DoctrineMuseum(
            id: $museum->id,
            email: $museum->email,
            name: $museum->name,
            description: $museum->description,
            info: $museum->info,
        );
        $markerBridge = new MarkerBridge();
        foreach ($museum->markers as $marker) {
            $doctrineMarker = $markerBridge->convertFromModel($marker);
            $doctrineMuseum->addMarker(
                $doctrineMarker
            );
        }

        return $doctrineMuseum;
    }

    public function toModel(DoctrineMuseum $entity): Museum
    {
        $markerBridge = new MarkerBridge();
        $markers = $entity->getMarkers()->map(fn(DoctrineMarker $doctrineMarker) => $markerBridge->toModel($doctrineMarker))->toArray();
        
        return new Museum(
            id: $entity->getId(),
            email: $entity->getEmail(),
            name: $entity->getName(),
            description: $entity->getDescription(),
            info: $entity->getInfo(),
            markers: $markers,
            uuid: $entity->getUuid(),
            createdAt: $entity->getCreatedAt(),
            updated: $entity->getUpdated(),
        );

    }
}