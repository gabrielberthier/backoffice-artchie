<?php

namespace App\Domain\Models\Assets;

use App\Domain\Models\Assets\AbstractAsset;

class PictureAsset extends AbstractAsset
{
  public function __construct()
  {
    parent::__construct('picture');
  }
}
