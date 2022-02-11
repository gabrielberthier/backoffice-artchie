<?php

namespace App\Domain\DTO\Asset;

abstract class Asset
{
  public function __construct(
    private string $name,
    private string $path,
    private ?string $url
  ) {
  }



  /**
   * Get the value of url
   */
  public function getUrl(): string
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
}
