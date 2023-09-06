<?php

declare(strict_types=1);

namespace App\Data\Entities\Doctrine;

use App\Data\Entities\Contracts\ModelCoercionInterface;
use App\Data\Entities\Contracts\ModelParsingInterface;
use App\Data\Entities\Doctrine\DoctrineAsset;
use App\Data\Entities\Doctrine\DoctrineMuseum;
use App\Data\Entities\Doctrine\Traits\TimestampsTrait;
use App\Data\Entities\Doctrine\Traits\UuidTrait;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Marker\Marker;
use App\Domain\Models\Museum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Table;

/**
 * @implements ModelCoercionInterface<Marker>
 * @implements ModelParsingInterface<Marker>
 */
#[Entity, Table(name: "markers"), HasLifecycleCallbacks]
class DoctrineMarker implements ModelCoercionInterface, ModelParsingInterface
{
    use TimestampsTrait;
    use UuidTrait;

    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    protected ?int $id;

    #[
        ManyToOne(targetEntity: DoctrineMuseum::class, inversedBy: "markers"),
        JoinColumn(name: "museum_id", referencedColumnName: "id")
    ]
    private ?DoctrineMuseum $museum;

    #[Column(type: "string", nullable: false)]
    private ?string $name;

    #[Column(type: "text", nullable: true)]
    private ?string $text;

    #[Column(type: "string", nullable: true)]
    private ?string $title;

    #[
        OneToOne(
            targetEntity: DoctrineMarkerAsset::class,
            mappedBy: "marker",
            cascade: ["persist", "remove"]
        )
    ]
    private ?DoctrineMarkerAsset $asset = null;

    #[Column(type: "boolean", nullable: false)]
    private bool $isActive = true;

    /**
     * One marker has one or many resources. This is the inverse side.
     *
     * @var Collection<DoctrinePlacementObject>
     */
    #[
        OneToMany(
            targetEntity: DoctrinePlacementObject::class,
            mappedBy: "marker",
            cascade: ["persist", "remove"]
        )
    ]
    private Collection $resources;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
    }

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
            "name" => $this->name,
            "text" => $this->text,
            "title" => $this->title,
            "asset" => $this->asset?->getAsset(),
            "resources" => $this->resources->toArray(),
            "isActive" => $this->isActive,
        ];
    }

    /** @return Collection<DoctrinePlacementObject> */
    public function getResources()
    {
        return $this->resources;
    }

    public function addResources(array $resource = []): self
    {
        foreach ($resource as $obj) {
            $obj->setMarker($this);
            $this->resources->add($obj);
        }

        return $this;
    }

    public function setResources(Collection $collection): self
    {
        $this->resources->clear();

        $this->resources = $collection;

        return $this;
    }

    public function addResource(DoctrinePlacementObject $resource): self
    {
        $resource->setMarker($this);
        $this->resources->add($resource);

        return $this;
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

    public function getText()
    {
        return $this->text;
    }

    public function setText($text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMuseum(): ?DoctrineMuseum
    {
        return $this->museum;
    }

    public function setMuseum(?DoctrineMuseum $museum): self
    {
        $this->museum = $museum;

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

    public function getAsset(): ?DoctrineMarkerAsset
    {
        return $this->asset;
    }

    public function setAsset(?DoctrineMarkerAsset $asset): self
    {
        $this->asset = $asset;

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

    public function toModel(): Marker
    {
        $resources = $this->getResources()->map(
            static fn (DoctrinePlacementObject $el) => $el->toModel()
        );

        $asset = $this->getAsset()?->getAsset()?->toModel();

        return new Marker(
            id: $this->id,
            museum: $this->museum,
            name: $this->name,
            text: $this->text,
            title: $this->title,
            isActive: $this->isActive,
            createdAt: $this->createdAt,
            updated: $this->updated,
            uuid: $this->uuid,
            resources: $resources,
            asset: $asset,
        );
    }

    /** @param Marker $model */
    public function fromModel(object $model): static
    {
        $asset = $model->asset;
        # Basic info
        $this->name = $model->name;
        $this->text = $model->text;
        $this->title = $model->title;
        $this->id = $model->id;
        $this->createdAt = $model->createdAt;
        $this->updated = $model->updated;
        $this->uuid = $model->uuid;

        if ($asset instanceof AbstractAsset) {
            $doctrineAsset = new DoctrineAsset();
            $markerAsset = new DoctrineMarkerAsset($this, $doctrineAsset->fromModel($asset));

            $this->asset = $markerAsset;
        }

        if ($model->museum instanceof Museum) {
            $doctrineMuseum = new DoctrineMuseum();
            $this->setMuseum($doctrineMuseum->fromModel($model->museum));
        }


        foreach ($model->resources as $resource) {
            $doctrinePlacementObject = new DoctrinePlacementObject();
            $this->addResource($doctrinePlacementObject->fromModel($resource));
        }

        return $this;
    }
}
