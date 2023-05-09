<?php

namespace App\Domain\Models\Assets\Types\Factories;

use App\Domain\Dto\Asset\Command\CreateAsset;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Assets\TextureAsset;
use App\Domain\Models\Assets\Types\Exceptions\NotAllowedAssetType;
use App\Domain\Models\Assets\Types\Helpers\AllowedExtensionChecker;
use App\Domain\Models\Assets\Types\Interfaces\ConstrainedAssetFactoryInterface;



class TextureAssetFactory implements ConstrainedAssetFactoryInterface
{
  public function __construct(private AllowedExtensionChecker $checker)
  {
  }
  function create(CreateAsset $command): AbstractAsset
  {
    if ($this->checker->isAllowed($command, $this)) {
      $asset = new TextureAsset();
      $asset->fromCommand($command);

      return $asset;
    }

    throw new NotAllowedAssetType();
  }

  function allowedFormats(): array|string
  {
    return [
      "BMP",
      "JPG",
      "PNG",
      'JPEG',
    ];
  }
}