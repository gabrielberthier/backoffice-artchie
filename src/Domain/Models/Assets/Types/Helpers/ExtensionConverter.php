<?php

namespace App\Domain\Models\Assets\Types\Helpers;


use App\Domain\Models\Assets\Types\Interfaces\ConstrainedAssetFactoryInterface;

class ExtensionConverter
{
  public function formatsToUpper(ConstrainedAssetFactoryInterface $assetFactoryInterface): array
  {
    $formats = $assetFactoryInterface->allowedFormats();
    if (is_array($formats)) {
      return array_map(static fn(string $format) => strtoupper($format), $formats);
    }

    return [
      strtoupper($formats)
    ];
  }
}
