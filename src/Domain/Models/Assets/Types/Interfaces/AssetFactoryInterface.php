<?php

namespace App\Domain\Models\Assets\Types\Interfaces;

use App\Domain\DTO\Asset\Command\CreateAsset;
use App\Domain\Models\Assets\AbstractAsset;

interface AssetFactoryInterface
{
  public function create(CreateAsset $command): AbstractAsset;
}
