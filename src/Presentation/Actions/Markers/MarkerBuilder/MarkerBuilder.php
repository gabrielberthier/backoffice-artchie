<?php

namespace App\Presentation\Actions\Markers\MarkerBuilder;

use App\Domain\DTO\Asset\Command\CreateAsset;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Assets\Types\AssetFactoryFacade;
use App\Domain\Models\Marker\Marker;
use App\Domain\Models\Marker\MarkerAsset;
use App\Domain\Models\PlacementObject\PlacementObject;
use App\Domain\Models\PlacementObject\PosedAsset;

class MarkerBuilder
{
    public function __construct(
        private Marker $marker,
        private AssetFactoryFacade $assetFactory
    ) {
    }

    public function getMarker(): Marker
    {
        return $this->marker;
    }

    public function prepareMarker(array|object $body): self
    {
        if (is_object($body)) {
            $body = (array) $body;
        }
        [
            "marker_name" => $name,
            "marker_text" => $text,
            "marker_title" => $title,
        ] = $body;

        $this->marker->setName($name);
        $this->marker->setText($text);
        $this->marker->setTitle($title);

        return $this;
    }

    public function appendMarkerAsset(array|object $body): self
    {
        $markerAsset = new MarkerAsset(
            $this->marker,
            $this->prepareAsset($body)
        );
        $this->marker->setAsset($markerAsset);

        return $this;
    }

    public function appendResource(array|object $body): self
    {
        $resource = new PlacementObject();
        if (is_object($body)) {
            $body = (array) $body;
        }
        $name = $body["pose_object_name"];
        $resource->setName($name);

        if (isset($body["asset"])) {
            $asset = new PosedAsset(
                $resource,
                $this->prepareAsset($body["asset"])
            );
            $resource->setAsset($asset);
        }

        $this->marker->addResource($resource);

        return $this;
    }

    private function prepareAsset(null|array|object $body): ?AbstractAsset
    {
        /** @var null|AbstractAsset */
        $asset = null;
        if (is_object($body)) {
            $body = (array) $body;
        }

        if (is_array($body)) {
            list(
                "file_name" => $fileName,
                "path" => $path,
                "url" => $url,
                "original_name" => $originalName,
            ) = $body;

            return $this->assetFactory->create(
                new CreateAsset(
                    $path,
                    $fileName,
                    $originalName,
                    $url
                )
            );
        }

        return $asset;
    }
}
