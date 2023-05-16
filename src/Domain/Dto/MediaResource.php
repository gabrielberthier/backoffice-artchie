<?php

namespace App\Domain\Dto;


final readonly class MediaResource
{
  public function __construct(
    public string $path,
    public string $name
  ) {
  }

}