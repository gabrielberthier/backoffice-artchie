<?php

namespace App\Data\Protocols\Resources;


use App\Domain\Exceptions\Museum\MuseumNotFoundException;

interface ResourcesDownloaderInterface
{
  /**
   * Returns all mapped marker instances from a museum
   *
   * @throws MuseumNotFoundException
   * 
   * @param string $uuid
   * 
   * @return \App\Domain\Dto\Asset\Transference\MarkerResource[]
   */
  public function transport(int $id): array;
}