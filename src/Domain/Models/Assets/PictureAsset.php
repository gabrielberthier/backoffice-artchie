<?php

namespace App\Domain\Models\Assets;

use App\Domain\Models\Assets\AbstractAsset;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class PictureAsset extends AbstractAsset
{
  public function __construct()
  {
    parent::__construct('picture');
  }
}