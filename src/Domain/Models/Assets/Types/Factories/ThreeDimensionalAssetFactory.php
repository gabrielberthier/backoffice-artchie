<?php

namespace App\Domain\Models\Assets\Types\Factories;

use App\Domain\DTO\Asset\Command\CreateAsset;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Assets\ThreeDimensionalAsset;
use App\Domain\Models\Assets\Types\Interfaces\ConstrainedAssetFactoryInterface;


class ThreeDimensionalAssetFactory implements ConstrainedAssetFactoryInterface
{
    public function __construct(private TextureAssetFactory $textureAssetFactory)
    {
    }

    function create(CreateAsset $command): AbstractAsset
    {
        $asset = new ThreeDimensionalAsset();
        $asset->fromCommand($command);
        foreach ($command->children() as $child) {
            $asset->addTexture($this->textureAssetFactory->create($child));
        }

        return $asset;
    }

    function allowedFormats(): array|string
    {
        return [
            'obj', 'fbx', 'glb', 'gltf'
        ];
    }
}
