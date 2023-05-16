<?php

namespace App\Data\Protocols\Media;

interface MediaHostParentInterface extends MediaHostInterface
{
  /**
   * Returns the dependent entities
   *
   * @return MediaHostInterface[]
   */
  public function children(): array;
}
