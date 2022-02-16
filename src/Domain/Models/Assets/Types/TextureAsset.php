<?php

namespace App\Domain\Models\Assets\Types;

use App\Domain\Models\Assets\AbstractAsset;

/**
 * @ORM\Entity
 */
class TextureAsset extends AbstractAsset
{
  public function __construct()
  {
    parent::__construct('texture');
  }

  function allowedFormats(): array
  {
    return [
      "BMP", "JPG", "PNG", 'JPEG',
    ];
  }
}
