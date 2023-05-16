<?php

namespace App\Data\Protocols\Media;

use App\Domain\Models\Assets\AbstractAsset;
use JsonSerializable;

interface MediaHostInterface extends JsonSerializable
{
  public function assetInformation(): ?AbstractAsset;

  public function accept(MediaCollectorInterface $visitor): void;

  public function namedBy(): string;
}
