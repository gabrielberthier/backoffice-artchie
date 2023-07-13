<?php

namespace App\Data\Protocols\Media;

use App\Domain\Dto\MediaResource;


interface MediaCollectorInterface
{

  public function visit(MediaHostInterface $mediaAdapters): void;

  /**
   * Collection of media files
   *
   * @return MediaResource[]
   */
  public function collect(): array;
}