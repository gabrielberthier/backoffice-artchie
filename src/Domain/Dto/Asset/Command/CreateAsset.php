<?php

namespace App\Domain\Dto\Asset\Command;

class CreateAsset
{
  public function __construct(
    public readonly string $path,
    public readonly string $fileName,
    public readonly string $originalName,
    public readonly ?string $url,
    private array $children = []
  ) {
  }

  public function mimeType(): string
  {
    $detector = new \League\MimeTypeDetection\FinfoMimeTypeDetector();

    return $detector->detectMimeTypeFromPath($this->path) ?? "";
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