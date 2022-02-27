<?php

namespace App\Domain\DTO\Asset\Command;

use Mimey\MimeTypes;

class CreateAsset
{
  public function __construct(
    public string $path,
    public string $fileName,
    public string $originalName,
    public ?string $url,
    private array $children = []
  ) {
  }

  public function mimeType(): string
  {
    $mimes = new MimeTypes();

    return $mimes->getMimeType($this->extension()) ?? "";
  }

  public function extension()
  {
    return pathinfo($this->path, PATHINFO_EXTENSION);
  }

  public function addChild(self $child)
  {
    $this->children[] = $child;
  }

  /**
   * Returns all assets related to this one
   *
   * @return self[]
   */
  public function children(): array
  {
    return $this->children;
  }
}
