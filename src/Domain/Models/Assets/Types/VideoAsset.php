<?php

namespace App\Domain\Models\Assets\Types;

use App\Domain\Models\Assets\AbstractAsset;

/**
 * @ORM\Entity
 */
class VideoAsset extends AbstractAsset
{
  public function __construct()
  {
    parent::__construct('video');
  }

  function allowedFormats(): array
  {
    return ['MP4'];
  }
}
