<?php

namespace App\Domain\Models\Assets\Types;

use App\Domain\Dto\Asset\Command\CreateAsset;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Assets\Types\Exceptions\NotAllowedAssetType;
use App\Domain\Models\Assets\Types\Factories\PictureAssetFactory;
use App\Domain\Models\Assets\Types\Factories\TextureAssetFactory;
use App\Domain\Models\Assets\Types\Factories\ThreeDimensionalAssetFactory;
use App\Domain\Models\Assets\Types\Factories\VideoAssetFactory;
use App\Domain\Models\Assets\Types\Helpers\AllowedExtensionChecker;
use App\Domain\Models\Assets\Types\Interfaces\AssetFactoryInterface;
use Doctrine\Common\Collections\ArrayCollection;

class AssetFactoryFacade implements AssetFactoryInterface
{
  /**
   * Proxies to be cloned in create method
   *
   * @var AssetFactoryInterface[]
   */
  private array $factories = [];

  public function __construct(private AllowedExtensionChecker $allowedExtensionChecker)
  {
    $this->factories[] = new PictureAssetFactory();
    $this->factories[] = new VideoAssetFactory();
    $this->factories[] = new ThreeDimensionalAssetFactory();
    $this->factories[] = new TextureAssetFactory();
  }

  public function create(CreateAsset $command): AbstractAsset
  {
    foreach ($this->factories as $factory) {
      if ($this->allowedExtensionChecker->isAllowed($command, $factory)) {
        $childrenAssets = [];
        foreach ($command->children() as $child) {
          $childrenAssets[] = $this->create($child);
        }
        
        $asset = $factory->create($command);
        $asset->setChildren(new ArrayCollection($childrenAssets));

        return $asset;
      }
    }

    throw new NotAllowedAssetType();
  }
}