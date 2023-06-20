<?php

declare(strict_types=1);

namespace App\Data\Entities\Doctrine;

use App\Data\Entities\Doctrine\Traits\TimestampsTrait;
use App\Data\Entities\Doctrine\Traits\UuidTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;


#[Entity, Table(name: 'placement_objects'), HasLifecycleCallbacks]
class DoctrinePlacementObject
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
        ManyToOne(targetEntity: DoctrineMarker::class, inversedBy: "resources"),
        JoinColumn(name: "marker_id", referencedColumnName: "id", onDelete: "CASCADE")
    ]
    private ?DoctrineMarker $marker;

    #[OneToOne(targetEntity: DoctrinePosedAsset::class, mappedBy: "posedObject", cascade: ["persist", "remove"])]
    private ?DoctrinePosedAsset $asset = null;

    public function assetInformation(): ?DoctrineAsset
    {
        return $this->asset?->getAsset();
    }

    public function namedBy(): string
    {
        return $this->name;
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
    public function getMarker(): DoctrineMarker
    {
        return $this->marker;
    }

    /**
     * Set many resources have one marker. This is the owning side.
     *
     * @return self
     */
    public function setMarker(DoctrineMarker $marker)
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
    public function getAsset(): ?DoctrinePosedAsset
    {
        return $this->asset;
    }

    /**
     * Set the value of asset.
     *
     * @return self
     */
    public function setAsset(DoctrinePosedAsset $asset)
    {
        $this->asset = $asset;

        return $this;
    }

    public function getMediaAsset(): ?DoctrineAsset
    {
        return $this->asset?->getAsset();
    }
}