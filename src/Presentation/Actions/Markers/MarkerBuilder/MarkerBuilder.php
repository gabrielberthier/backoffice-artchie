<?php

namespace App\Presentation\Actions\Markers\MarkerBuilder;

use App\Domain\Models\AbstractAsset;
use App\Domain\Models\Marker;
use App\Domain\Models\MarkerAsset;
use App\Domain\Models\PlacementObject;
use App\Domain\Models\PosedAsset;

class MarkerBuilder
{
    private Marker $marker;

    public function __construct()
    {
        $this->marker = new Marker();
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
        $name = $body['marker_name'];
        $text = $body['marker_text'];
        $title = $body['marker_title'];

        $this->marker->setName($name);
        $this->marker->setText($text);
        $this->marker->setTitle($title);

        return $this;
    }

    public function appendMarkerAsset(array|object $body): self
    {
        $this->marker->setAsset($this->prepareAsset(new MarkerAsset(), $body));

        return $this;
    }

    public function appendResource(array|object $body): self
    {
        $resource = new PlacementObject();
        if (is_object($body)) {
            $body = (array) $body;
        }
        $name = $body['pose_object_name'];
        $resource->setName($name);

        if (isset($body['asset'])) {
            $resource->setAsset($this->prepareAsset(new PosedAsset(), $body['asset']));
        }

        $this->marker->addResource($resource);

        return $this;
    }

    private function prepareAsset(AbstractAsset $asset, null|array|object $body): ?AbstractAsset
    {
        if (is_object($body)) {
            $body = (array) $body;
        }

        if (is_array($body)) {
            list('file_name' => $fileName, 'media_type' => $mediaType, 'path' => $path, 'url' => $url) = $body;

            return $asset
                ->setFileName($fileName)
                ->setMediaType($mediaType)
                ->setPath($path)
                ->setUrl($url)
          ;
        }

        return null;
    }
}
