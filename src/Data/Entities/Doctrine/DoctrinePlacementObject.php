<?php

declare(strict_types=1);

namespace App\Data\Entities\Doctrine;

use App\Data\Entities\Contracts\ModelCoercionInterface;
use App\Data\Entities\Contracts\ModelParsingInterface;
use App\Data\Entities\Doctrine\Traits\TimestampsTrait;
use App\Data\Entities\Doctrine\Traits\UuidTrait;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Marker\Marker;
use App\Domain\Models\PlacementObject\PlacementObject;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @implements ModelParsingInterface<PlacementObject>
 * @implements ModelCoercionInterface<PlacementObject>
 */
#[Entity, Table(name: "placement_objects"), HasLifecycleCallbacks]
class DoctrinePlacementObject implements ModelParsingInterface, ModelCoercionInterface
{
    use TimestampsTrait;
    use UuidTrait;

    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    protected ?int $id;

    #[Column(type: "string")]
    private ?string $name;

    #[Column(type: "boolean")]
    private bool $isActive = true;

    /**
     * Many resources have one marker. This is the owning side.
     *
     */
    #[
        ManyToOne(targetEntity: DoctrineMarker::class, inversedBy: "resources"),
        JoinColumn(
            name: "marker_id",
            referencedColumnName: "id",
            onDelete: "CASCADE"
        )
    ]
    private ?DoctrineMarker $marker;

    #[
        OneToOne(
            targetEntity: DoctrinePosedAsset::class,
            mappedBy: "posedObject",
            cascade: ["persist", "remove"]
        )
    ]
    private ?DoctrinePosedAsset $asset = null;

    public function assetInformation(): ?DoctrineAsset
    {
        return $this->asset?->getAsset();
    }

    public function namedBy(): string
    {
        return $this->name;
    }

    /** @return array */
    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->id,
            "uuid" => $this->uuid,
            "name" => $this->name,
            "asset" => $this->asset?->getAsset(),
        ];
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getMarker(): ?DoctrineMarker
    {
        return $this->marker;
    }

    public function setMarker(?DoctrineMarker $marker): self
    {
        $this->marker = $marker;

        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getAsset(): ?DoctrinePosedAsset
    {
        return $this->asset;
    }

    public function setAsset(DoctrinePosedAsset $asset): self
    {
        $this->asset = $asset;

        return $this;
    }

    public function getMediaAsset(): ?DoctrineAsset
    {
        return $this->asset?->getAsset();
    }

    public function toModel(): PlacementObject
    {
        $asset = $this->getAsset()?->getAsset()?->toModel();

        return new PlacementObject(
            id: $this->id,
            name: $this->name,
            isActive: $this->isActive,
            marker: $this->marker?->toModel(),
            createdAt: $this->createdAt,
            updated: $this->updated,
            uuid: $this->uuid,
            asset: $asset,
        );
    }

    /** @param PlacementObject $model */
    public function fromModel(object $model): static
    {
        $this->createdAt = $model->createdAt;
        $this->id = $model->id;
        $this->isActive = $model->isActive;
        $this->name = $model->name;
        $this->updated = $model->updated;
        $this->uuid = $model->uuid;

        if ($model->asset instanceof AbstractAsset) {
            $doctrineAsset = new DoctrineAsset();
            $doctrineAsset->fromModel($model->asset);
            $doctrinePosedAsset = new DoctrinePosedAsset($this, $doctrineAsset);
            $this->setAsset($doctrinePosedAsset);
        }

        if ($model->marker instanceof Marker) {
            $doctrineMarker = new DoctrineMarker();
            $this->setMarker($doctrineMarker->fromModel($model->marker));
        }

        return $this;
    }
}
