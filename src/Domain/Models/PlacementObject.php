<?php

declare(strict_types=1);

namespace App\Domain\Models;

use App\Domain\Models\Traits\TimestampsTrait;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="placement_objects")
 */
class PlacementObject implements JsonSerializable
{
    use TimestampsTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id;
    /** @ORM\Column(type="uuid", unique=true) */
    private UuidInterface $uuid;
    /** @ORM\Column(type="string") */
    private string $name;
    /** @ORM\Column(type="boolean") */
    private bool $isActive = true;

    /**
     * Many resources have one marker. This is the owning side.
     *
     * @ORM\ManyToOne(targetEntity="Marker", inversedBy="resources")
     * @ORM\JoinColumn(name="marker_id", referencedColumnName="id")
     */
    private ?Marker $marker;

    /** @ORM\OneToOne(targetEntity="PosedAsset", mappedBy="posedObject", cascade={"persist", "remove"}) */
    private ?AbstractAsset $asset = null;

    public function __construct()
    {
        $this->uuid = $uuid ?? Uuid::uuid4();
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'marker' => [
                'id' => $this->marker->getId(),
                'name' => $this->marker->getName(),
                'dataInfo' => [
                    'text' => $this->marker->getText(),
                    'title' => $this->marker->getTitle(),
                ],
            ],
            'asset' => $this->asset,
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
     * Get the value of uuid.
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set the value of uuid.
     *
     * @param mixed $uuid
     *
     * @return self
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

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
    public function getAsset(): AbstractAsset
    {
        return $this->asset;
    }

    /**
     * Set the value of asset.
     *
     * @return self
     */
    public function setAsset(AbstractAsset $asset)
    {
        $this->asset = $asset;

        return $this;
    }
}
