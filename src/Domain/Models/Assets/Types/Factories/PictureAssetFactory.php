<?php

namespace App\Domain\Models\Assets\Types\Factories;

use App\Domain\DTO\Asset\Command\CreateAsset;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Assets\PictureAsset;
use App\Domain\Models\Assets\Types\Interfaces\ConstrainedAssetFactoryInterface;



class PictureAssetFactory implements ConstrainedAssetFactoryInterface
{
  function create(CreateAsset $command): AbstractAsset
  {
    $asset = new PictureAsset();
    $asset->fromCommand($command);

    return $asset;
  }

  public function allowedFormats(): array|string
  {
    return [
      "BMP", "TIF", "TGA", "JPG", "PNG", 'JPEG',
    ];
  }
}
