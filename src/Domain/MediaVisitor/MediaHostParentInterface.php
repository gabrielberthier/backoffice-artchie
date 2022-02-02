<?php

namespace App\Domain\MediaVisitor;

interface MediaHostParentInterface extends MediaHostInterface
{
  /**
   * Returns the dependent entities
   *
   * @return MediaHostInterface[]
   */
  public function children(): array;
}
