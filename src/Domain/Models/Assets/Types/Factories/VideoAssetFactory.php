<?php

namespace App\Domain\Models\Assets\Types\Factories;

use App\Domain\Dto\Asset\Command\CreateAsset;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Assets\Types\Interfaces\ConstrainedAssetFactoryInterface;
use App\Domain\Models\Assets\VideoAsset;

class VideoAssetFactory implements ConstrainedAssetFactoryInterface
{
  function create(CreateAsset $command): AbstractAsset
  {
    $asset = new VideoAsset();
    $asset->fromCommand($command);

    return $asset;
  }

  function allowedFormats(): array|string
  {
    return 'MP4';
  }
}