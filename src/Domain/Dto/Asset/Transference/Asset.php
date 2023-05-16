<?php

namespace App\Domain\Dto\Asset\Transference;

use JsonSerializable;

abstract class Asset implements JsonSerializable
{
  public function __construct(
    public readonly string $name,
    public readonly string $path,
    public readonly ?string $url = null
  ) {
  }


  public function jsonSerialize(): mixed
  {
    return [
      'name' => $this->name,
      'path' => $this->path,
      'url' => $this->url
    ];
  }
}