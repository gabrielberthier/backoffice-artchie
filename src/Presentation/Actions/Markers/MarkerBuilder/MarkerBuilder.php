<?php

namespace App\Presentation\Actions\Markers\MarkerBuilder;

use App\Domain\Dto\Asset\Command\CreateAsset;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Assets\Types\AssetFactoryFacade;
use App\Domain\Models\PlacementObject\PlacementObject;


class MarkerBuilder
{
    public function __construct(
        private AssetFactoryFacade $assetFactory
    ) {
    }

    public function makePlacementObject(array|object $body): PlacementObject
    {
        if (is_object($body)) {
            $body = (array) $body;
        }
        
        $name = $body["pose_object_name"];

        $asset = $this->prepareAsset($body["asset"]);

        return new PlacementObject(null, name: $name, asset: $asset, marker: null);
    }

    public function prepareAsset(null|array|object $body): ?AbstractAsset
    {
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