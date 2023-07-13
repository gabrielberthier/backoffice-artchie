<?php

namespace App\Domain\Models\Assets\Types\Factories;

use App\Domain\Dto\Asset\Command\CreateAsset;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Assets\ThreeDimensionalAsset;
use App\Domain\Models\Assets\Types\Interfaces\ConstrainedAssetFactoryInterface;


class ThreeDimensionalAssetFactory implements ConstrainedAssetFactoryInterface
{
    public function create(CreateAsset $command): AbstractAsset
    {
        $asset = new ThreeDimensionalAsset();
        return $asset->fromCommand($command);
    }

    public function allowedFormats(): array|string
    {
        return [
            'obj',
            'fbx',
            'glb',
            'gltf'
        ];
    }
}