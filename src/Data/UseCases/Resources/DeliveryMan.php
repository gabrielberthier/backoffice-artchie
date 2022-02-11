<?php

namespace App\Data\UseCases\Resources;

use App\Data\Protocols\Media\MediaHostInterface;
use App\Data\Protocols\Resources\ResourcesDownloaderInterface;
use App\Domain\DTO\Asset\Asset;
use App\Domain\DTO\Asset\AssetInfo;
use App\Domain\DTO\Asset\MarkerResource;
use App\Domain\DTO\Asset\PlacementResource;
use App\Domain\Exceptions\Museum\MuseumNotFoundException;
use App\Domain\Exceptions\Protocols\DomainException;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Marker\Marker;
use App\Domain\Models\Museum;
use App\Domain\Models\PlacementObject\PlacementObject;
use App\Domain\Repositories\MarkerRepositoryInterface;
use App\Domain\Repositories\MuseumRepository;
use App\Presentation\Actions\Markers\Utils\PresignedUrlCreator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class DeliveryMan implements ResourcesDownloaderInterface
{
  public function __construct(
    private MuseumRepository $museumRepository,
    private MarkerRepositoryInterface $repository,
    private PresignedUrlCreator $presignedUrlCreator
  ) {
  }

  /**
   * Returns all mapped marker instances from a museum
   *
   * @param string $uuid
   * @return array
   */
  public function transport(string $uuid): array
  {
    $museum = $this->museumRepository->findByUUID($uuid);
    return $this->doOrThrowIf(
      isset($museum),
      fn () =>
      $this->mapCollectionToAssets(
        $this->filterPlacementObjectsFromMarkers(
          $this->filterMarkers(
            $this->gatherMarkersFromMuseum($museum)
          )
        )
      ),
      new MuseumNotFoundException(
        "Could not identify a museum by this code"
      )
    );
  }

  /**
   * Maps over Markers Collection and return an array of assets
   *
   * @param Collection<Marker> $markers
   * 
   * @return MarkerResource[]
   */
  private function mapCollectionToAssets(Collection $markers): array
  {
    return $this->preventNotFoundAssets(
      $markers
        ->map(
          fn (Marker $el) => (new MarkerResource(
            ...$this->convertMediaMapperToAssetBase($el)
          ))->withInformation(new AssetInfo($el->getTitle(), $el->getText()))
            ->attachPlacementResources(
              $this->mapPlacementObjectsToPlacementResources(
                $el->getResources()
              )
            )
        )
    )
      ->toArray();
  }

  /**
   *
   * @param Collection<PlacementObject> $resources
   * 
   * @return PlacementResource[]
   */
  private function mapPlacementObjectsToPlacementResources(Collection $resources): array
  {
    return $this->preventNotFoundAssets(
      $resources
        ->map(
          fn (
            PlacementObject $po
          ) => new PlacementResource(
            ...$this->convertMediaMapperToAssetBase(
              $po
            )
          )
        )
    )
      ->toArray();
  }

  private function convertMediaMapperToAssetBase(MediaHostInterface $host): array
  {
    return [
      'name' => $host->namedBy(),
      'path' => $host->assetInformation()->getPath(),
      'url' => $this->assignUrl($host->assetInformation())
    ];
  }

  /**
   * @param AbstractAsset $abstractAsset
   * 
   * @return string|null
   */
  private function assignUrl(AbstractAsset $abstractAsset): ?string
  {
    return $this
      ->presignedUrlCreator
      ->setPresignedUrl(
        $abstractAsset
      );
  }

  private function preventNotFoundAssets(Collection $collection): Collection
  {
    return $collection->filter(fn (Asset $asset) => empty($asset->getUrl()));
  }

  private function doOrThrowIf(bool $condition, callable $callback, DomainException $domainException)
  {
    if ($condition) {
      return $callback();
    }

    throw $domainException;
  }

  /**
   * @param Collection<Marker> $collection
   * 
   * @return Collection<Marker>
   */
  private function filterMarkers(Collection $collection): Collection
  {
    return $this->getOnlySetAssets($collection);
  }

  /**
   * @param Museum $museum
   * 
   * @return Collection<Marker>
   */
  private function gatherMarkersFromMuseum(Museum $museum): Collection
  {
    return new ArrayCollection(
      $this->repository
        ->findAllByMuseum($museum)
        ->getItems()
    );
  }

  /**
   * @param Collection<Marker> $markers
   * 
   * @return Collection<Marker> $markers
   */
  private function filterPlacementObjectsFromMarkers(Collection $markers): Collection
  {
    iterator_apply(
      $markers,
      fn (Marker $marker) =>
      !!$marker
        ->setResources(
          $this->getOnlySetAssets(
            $marker->getResources()
          )
        )
    );

    return $markers;
  }

  private function getOnlySetAssets(Collection $collection)
  {
    return $collection->filter(
      function (MediaHostInterface $el) {
        return !is_null(
          $el->assetInformation()
        );
      }
    );
  }
}
