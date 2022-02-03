<?php

namespace App\Data\UseCases\Media;

use App\Data\Protocols\Media\MediaCollectorInterface;
use App\Data\Protocols\Media\MediaHostInterface;
use App\Data\Protocols\Media\MediaHostParentInterface;
use App\Domain\DTO\MediaResource;
use App\Domain\Models\Assets\AbstractAsset;

class MediaCollectorVisitor implements MediaCollectorInterface
{
  /**
   * Collection of media files
   *
   * @var MediaResource[]
   */
  private array $collection = [];


  function collect(): array
  {
    return $this->collection;
  }


  function visit(MediaHostInterface $mediaAdapter): void
  {
    $info = $mediaAdapter->assetInformation();
    if ($info) {
      $this->collection[] = $this->makeAssetInfo($info, $mediaAdapter->namedBy());
    }
    if ($mediaAdapter instanceof MediaHostParentInterface) {
      foreach ($mediaAdapter->children() as $vals) {
        $this->visit($vals);
      }
    }
  }

  public function makeAssetInfo(AbstractAsset $asset, string $name): MediaResource
  {
    return new MediaResource($asset->getPath(), $name);
  }
}
