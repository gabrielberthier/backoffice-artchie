<?php

namespace App\Domain\Models\Assets\Types\Factories;

use App\Domain\Dto\Asset\Command\CreateAsset;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Assets\Types\Interfaces\ConstrainedAssetFactoryInterface;
use App\Domain\Models\Assets\VideoAsset;

class VideoAssetFactory implements ConstrainedAssetFactoryInterface
{
  public function create(CreateAsset $command): AbstractAsset
  {
    $asset = new VideoAsset();
    return $asset->fromCommand($command);
  }

  public function allowedFormats(): array|string
  {
    return 'MP4';
  }
}