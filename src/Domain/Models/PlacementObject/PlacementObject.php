<?php

declare(strict_types=1);

namespace App\Domain\Models\PlacementObject;

use App\Data\Protocols\Media\MediaCollectorInterface;
use App\Data\Protocols\Media\MediaHostInterface;
use App\Domain\Contracts\ModelInterface;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Marker\Marker;
use App\Domain\Models\Traits\TimestampsTrait;
use App\Domain\Models\Traits\UuidTrait;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;


#[Entity, Table(name: 'placement_objects'), HasLifecycleCallbacks]
class PlacementObject implements ModelInterface, MediaHostInterface
{
    use TimestampsTrait;
    use UuidTrait;

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[Column(type: 'string')]
    private string $name;
    #[Column(type: 'boolean')]
    private bool $isActive = true;

    /**
     * Many resources have one marker. This is the owning side.
     *
     */
    #[
        ManyToOne(targetEntity: Marker::class, inversedBy: "resources"),
        JoinColumn(name: "marker_id", referencedColumnName: "id", onDelete: "CASCADE")
    ]
    private ?Marker $marker;

    #[OneToOne(targetEntity: PosedAsset::class, mappedBy: "posedObject", cascade: ["persist", "remove"])]
    private ?PosedAsset $asset = null;



    public function assetInformation(): ?AbstractAsset
    {
        return $this->asset?->getAsset();
    }

    public function namedBy(): string
    {
        return $this->name;
    }

    public function accept(MediaCollectorInterface $visitor): void
    {
        $visitor->visit($this);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'asset' => $this->asset?->getAsset(),
        ];
    }

    /**
     * Get the value of name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name.
     *
     * @param mixed $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id.
     *
     * @param mixed $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get many resources have one marker. This is the owning side.
     */
    public function getMarker(): Marker
    {
        return $this->marker;
    }

    /**
     * Set many resources have one marker. This is the owning side.
     *
     * @return self
     */
    public function setMarker(Marker $marker)
    {
        $this->marker = $marker;

        return $this;
    }

    /**
     * Get the value of isActive.
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * Set the value of isActive.
     *
     * @return self
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get the value of asset.
     */
    public function getAsset(): ?PosedAsset
    {
        return $this->asset;
    }

    /**
     * Set the value of asset.
     *
     * @return self
     */
    public function setAsset(PosedAsset $asset)
    {
        $this->asset = $asset;

        return $this;
    }

    public function getMediaAsset(): ?AbstractAsset
    {
        if ($this->asset) {
            return $this->asset->getAsset();
        }
        return null;
    }
}
