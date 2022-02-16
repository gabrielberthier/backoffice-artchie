<?php

namespace App\Domain\Models\Assets\Types;

use App\Domain\Models\Assets\AbstractAsset;

/**
 * @ORM\Entity
 */
class PictureAsset extends AbstractAsset
{
  public function __construct()
  {
    parent::__construct('video');
  }

  public function allowedFormats(): array
  {
    return [
      "BMP", "TIF", "TGA", "JPG", "PNG", 'JPEG',
    ];
  }
}
