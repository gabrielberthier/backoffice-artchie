<?php

namespace App\Domain\Models\Assets;

use App\Domain\Models\Assets\AbstractAsset;

class VideoAsset extends AbstractAsset
{
  public function __construct()
  {
    parent::__construct('video');
  }
}
