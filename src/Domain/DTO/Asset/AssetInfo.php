<?php

namespace App\Domain\DTO\Asset;

class AssetInfo
{
  public function __construct(
    private string $title,
    private string $description
  ) {
  }

  /**
   * Get the value of title
   */
  public function getTitle(): string
  {
    return $this->title;
  }


  /**
   * Get the value of description
   */
  public function getDescription(): string
  {
    return $this->description;
  }
}
