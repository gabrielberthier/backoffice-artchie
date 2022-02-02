<?php

namespace App\Domain\DTO;


final class MediaResource
{
  public function __construct(private string $path, private string $name)
  {
  }

  public function path(): string
  {
    return $this->path;
  }

  public function name(): string
  {
    return $this->name;
  }
}
