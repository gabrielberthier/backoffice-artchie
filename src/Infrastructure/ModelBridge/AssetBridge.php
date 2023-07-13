<?php

namespace App\Infrastructure\ModelBridge;

use App\Data\Entities\Doctrine\DoctrineAsset;
use App\Domain\Dto\Asset\Command\CreateAsset;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Assets\Types\AssetFactoryFacade;
use App\Domain\Models\Assets\Types\Helpers\AllowedExtensionChecker;

class AssetBridge
{
    public function convertFromModel(AbstractAsset $asset): DoctrineAsset
    {
        $doctrineAsset = new DoctrineAsset();
        $doctrineAsset
            ->setCreatedAt($asset->getCreatedAt())
            ->setFileName($asset->getFileName())
            ->setId($asset->getId())
            ->setMediaType($asset->getMediaType())
            ->setMimeType($asset->getMimeType())
            ->setOriginalName($asset->getOriginalName())
            ->setPath($asset->getPath())
            ->setTemporaryLocation($asset->getTemporaryLocation())
            ->setUpdated($asset->getUpdated())
            ->setUrl($asset->getUrl())
            ->setUuid($asset->getUuid());
        if ($doctrineAsset->getParent() instanceof DoctrineAsset) {
            $doctrineAsset->setParent($this->convertFromModel($asset->getParent()));
        }

        foreach ($asset->getChildren() as $child) {
            $doctrineAsset->addChild(
                $this->convertFromModel($child)
            );
        }

        return $doctrineAsset;
    }

    public function toModel(DoctrineAsset $doctrineAsset): AbstractAsset
    {
        $children = $doctrineAsset->getChildren()->map(fn($el) => $this->toModel($el))->toArray();
        $createAsset = new CreateAsset(
            path: $doctrineAsset->getPath(),
            fileName: $doctrineAsset->getFileName(),
            originalName: $doctrineAsset->getOriginalName(),
            url: $doctrineAsset->getUrl(),
            children: $children,
        );

        $factoryFacade = new AssetFactoryFacade(new AllowedExtensionChecker());
        $asset = $factoryFacade->create($createAsset);
        $asset->setId($doctrineAsset->getId());
        $asset->setUuid($doctrineAsset->getUuid());
        $asset->setCreatedAt($doctrineAsset->getCreatedAt());
        $asset->setUpdated($doctrineAsset->getUpdated());

        return $asset;
    }
}