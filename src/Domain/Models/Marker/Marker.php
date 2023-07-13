<?php

declare(strict_types=1);

namespace App\Domain\Models\Marker;

use App\Data\Protocols\Media\MediaCollectorInterface;
use App\Data\Protocols\Media\MediaHostInterface;
use App\Data\Protocols\Media\MediaHostParentInterface;
use App\Domain\Contracts\ModelInterface;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Museum;
use App\Domain\Models\PlacementObject\PlacementObject;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

readonly class Marker implements ModelInterface, MediaHostParentInterface
{
    public function __construct(
        public ?int $id,
        public ?Museum $museum,
        public string $name,
        public ?string $text,
        public ?string $title,
        public ?AbstractAsset $asset = null,
        public bool $isActive = true,
        /** @var Collection<PlacementObject> */
        public Collection $resources = new ArrayCollection(), 
        public ?DateTimeInterface $createdAt = new DateTimeImmutable(), 
        public ?DateTimeInterface $updated = new DateTimeImmutable(), 
        public ?UuidInterface $uuid = null)
    {
    }


    public function assetInformation(): ?AbstractAsset
    {
        return $this->asset;
    }

    public function namedBy(): string
    {
        return $this->name;
    }

    public function accept(MediaCollectorInterface $visitor): void
    {
        $visitor->visit($this);

        foreach ($this->children() as $child) {
            $child->accept($visitor);
        }
    }

    /**
     * Returns the dependent entities
     *
     * @return MediaHostInterface[]
     */
    public function children(): array
    {
        return $this->resources->toArray();
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'text' => $this->text,
            'title' => $this->title,
            'asset' => $this->asset,
            'resources' => $this->resources->toArray(),
            'isActive' => $this->isActive,
        ];
    }

    /**
     * Add a resource variadic set to the collection
     */
    public function addResources(array $resource = []): self
    {
        foreach ($resource as $obj) {
            $this->resources->add($obj);
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param Collection<PlacementObject> $collection
     */
    public function setResources(Collection $collection): self
    {
        $this->resources->clear();

        $this->resources = $collection;

        return $this;
    }


    /**
     * Set the value of resource.
     *
     * @return self
     */
    public function addResource(PlacementObject $resource)
    {
        $this->resources->add($resource);

        return $this;
    }

    public function getMediaAsset(): ?AbstractAsset
    {
        return $this->asset;
    }
}