<?php

namespace App\Domain\Dto\Asset\Transference;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class MarkerResource extends Asset
{
  /** @var Collection<PlacementResource> */
  private Collection $placementResource;
  
  private ?AssetInfo $dataInfo = null;

  public function __construct(
    protected string $name,
    protected string $path,
    protected ?string $url
  ) {
    $this->placementResource = new ArrayCollection();
  }

  public function addElement(PlacementResource $el)
  {
    $this->placementResource->add($el);
  }

  public function withInformation(?AssetInfo $assetInfo): self
  {
    $this->dataInfo = $assetInfo;

    return $this;
  }

  /**
   *
   * @param PlacementResource[] $placementResources
   */
  public function attachPlacementResources(array $placementResources): self
  {
    foreach ($placementResources as $r) {
      $this->addElement($r);
    }

    return $this;
  }

  /**
   * Get the value of dataInfo
   */
  public function getDataInfo(): ?AssetInfo
  {
    return $this->dataInfo;
  }

  public function jsonSerialize(): mixed
  {
    return parent::jsonSerialize() + [
      'asset_info' => $this->dataInfo,
      'placement_objects' => $this->placementResource->toArray()
    ];
  }
}