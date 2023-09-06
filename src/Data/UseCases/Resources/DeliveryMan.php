<?php

namespace App\Data\UseCases\Resources;

use App\Data\Protocols\Media\MediaHostInterface;
use App\Data\Protocols\Resources\ResourcesDownloaderInterface;
use App\Domain\Dto\Asset\Transference\Asset as TransferenceAsset;
use App\Domain\Dto\Asset\Transference\AssetInfo as TransferenceAssetInfo;
use App\Domain\Dto\Asset\Transference\MarkerResource;
use App\Domain\Dto\Asset\Transference\PlacementResource as TransferencePlacementResource;
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
   *
   */
  public function transport(int $id): array
  {
    $museum = $this->museumRepository->findByID($id);
    try {
      return $this->doOrThrowIf(
        isset($museum),
        doCallback: fn() =>
        $this->mapCollectionToAssets(
          $this->filterPlacementObjectsFromMarkers(
            $this->filterMarkers(
              $this->gatherMarkersFromMuseum(
                $museum
              )
            )
          )
        ),
        orThrow: new MuseumNotFoundException(
          "Could not identify a museum by this code"
        )
      );
    } catch (\Throwable $throwable) {
      echo ($throwable);

      return [];
    }
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
      $markers->map(
        fn(Marker $el) => (
          new MarkerResource(
          ...$this->convertMediaMapperToAssetBase($el)
          )
        )
          ->withInformation(
            new TransferenceAssetInfo($el->title, $el->text)
          )
          ->attachPlacementResources(
            $this->mapPlacementObjectsToPlacementResources(
              $el->resources
            )
          )
      )
    )->toArray();
  }

  /**
   *
   * @param Collection<PlacementObject> $resources
   *
   * @return \App\Domain\Dto\Asset\Transference\PlacementResource[]
   */
  private function mapPlacementObjectsToPlacementResources(
    Collection $resources
  ): array {
    return $this->preventNotFoundAssets(
      $resources->map(
        fn(PlacementObject $po) => new TransferencePlacementResource(
          ...$this->convertMediaMapperToAssetBase($po)
        )
      )
    )->toArray();
  }

  private function convertMediaMapperToAssetBase(
    MediaHostInterface $host
  ): array {
    return [
      "name" => $host->namedBy(),
      "path" => $host->assetInformation()->getPath(),
      "url" => $this->assignUrl($host->assetInformation()),
    ];
  }

  private function assignUrl(AbstractAsset $abstractAsset): ?string
  {
    return $this->presignedUrlCreator->setPresignedUrl($abstractAsset);
  }

  private function preventNotFoundAssets(Collection $collection): Collection
  {
    return $collection->filter(
      static fn(TransferenceAsset $asset) => !($asset->url === null && $asset->url === "")
    );
  }

  private function doOrThrowIf(
    bool $condition,
    callable $doCallback,
    DomainException $orThrow
  ) {
    if ($condition) {
      return $doCallback();
    }

    throw $orThrow;
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
   * @return Collection<Marker>
   */
  private function gatherMarkersFromMuseum(Museum $museum): Collection
  {
    return new ArrayCollection(
      $this->repository->findAllByMuseum($museum)->getItems()
    );
  }

  /**
   * @param Collection<Marker> $markers
   *
   * @return Collection<Marker> $markers
   */
  private function filterPlacementObjectsFromMarkers(
    Collection $markers
  ): Collection {
    foreach ($markers as $marker) {
      $marker->setResources(
        $this->getOnlySetAssets(
          $marker->getResources()
        )
      );
    }

    return $markers;
  }

  private function getOnlySetAssets(Collection $collection)
  {
    return $collection->filter(static function (MediaHostInterface $el) {
        return !is_null($el->assetInformation());
    });
  }
}