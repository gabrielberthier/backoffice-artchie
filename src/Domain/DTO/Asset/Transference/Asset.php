<?php

namespace App\Domain\DTO\Asset\Transference;

use JsonSerializable;

abstract class Asset implements JsonSerializable
{
  public function __construct(
    protected string $name,
    protected string $path,
    protected ?string $url = null
  ) {
  }



  /**
   * Get the value of url
   */
  public function getUrl(): ?string
  {
    return $this->url;
  }

  /**
   * Set the value of url
   *
   * @return  self
   */
  public function setUrl(string $url)
  {
    $this->url = $url;

    return $this;
  }

  /**
   * Get the value of name
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Set the value of name
   *
   * @return  self
   */
  public function setName(string $name)
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Get the value of path
   */
  public function getPath(): string
  {
    return $this->path;
  }

  /**
   * Set the value of path
   *
   * @return  self
   */
  public function setPath(string $path)
  {
    $this->path = $path;

    return $this;
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
