<?php

namespace App\Data\Protocols\Media;

use App\Domain\DTO\MediaResource;


interface MediaCollectorInterface
{

  function visit(MediaHostInterface $mediaAdapters): void;

  /**
   * Collection of media files
   *
   * @return MediaResource[]
   */
  function collect(): array;
}
