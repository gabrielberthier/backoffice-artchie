<?php

namespace App\Domain\Dto\Asset\Transference;

use JsonSerializable;

class AssetInfo implements JsonSerializable
{
  public function __construct(
    public readonly string $title,
    public readonly string $description
  ) {
  }

  public function jsonSerialize(): mixed
  {
    return [
      'title' => $this->title,
      'text' => $this->description
    ];
  }
}