<?php

namespace App\Presentation\Actions\Markers\MarkerBuilder;

use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Assets\ThreeDimensionalAsset;
use App\Domain\Models\Assets\TwoDimensionalAsset;
use App\Domain\Models\Marker\Marker;
use App\Domain\Models\Marker\MarkerAsset;
use App\Domain\Models\PlacementObject\PlacementObject;
use App\Domain\Models\PlacementObject\PosedAsset;
use Mimey\MimeTypes;

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
        $markerAsset = new MarkerAsset($this->marker, $this->prepareAsset($body));
        $this->marker->setAsset($markerAsset);

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
            $asset = new PosedAsset($resource, $this->prepareAsset($body['asset']));
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
            list('file_name' => $fileName, 'path' => $path, 'url' => $url, 'original_name' => $originalName) = $body;
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            if (in_array($extension, ['obj', 'fbx', 'dae', '3ds'])) {
                $asset = new ThreeDimensionalAsset();
            } else {
                $asset = new TwoDimensionalAsset();
            }

            $mimes = new MimeTypes();

            $asset
                ->setFileName($fileName)
                ->setPath($path)
                ->setUrl($url)
                ->setOriginalName($originalName)
                ->setMimeType(($mimes->getMimeType($extension)))
          ;
        }

        return $asset;
    }
}
