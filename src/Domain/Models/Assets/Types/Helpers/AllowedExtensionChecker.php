<?php

namespace App\Domain\Models\Assets\Types\Helpers;

use App\Domain\Dto\Asset\Command\CreateAsset;
use App\Domain\Models\Assets\Types\Interfaces\ConstrainedAssetFactoryInterface;

class AllowedExtensionChecker
{
  public function isAllowed(CreateAsset $command, ConstrainedAssetFactoryInterface $factory): bool
  {
    $converter = new ExtensionConverter();
    return in_array(
      strtoupper($command->extension()),
      $converter->formatsToUpper($factory)
    );
  }
}