<?php

namespace App\Domain\Models\Assets\Types\Factories;

use App\Domain\Dto\Asset\Command\CreateAsset;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Assets\TextureAsset;
use App\Domain\Models\Assets\Types\Interfaces\ConstrainedAssetFactoryInterface;



class TextureAssetFactory implements ConstrainedAssetFactoryInterface
{
  public function create(CreateAsset $command): AbstractAsset
  {
    $asset = new TextureAsset();
    return $asset->fromCommand($command);
  }

  public function allowedFormats(): array|string
  {
    return [
      "BMP",
      "JPG",
      "PNG",
      'JPEG',
    ];
  }
}