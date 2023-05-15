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
use App\Domain\Models\Traits\TimestampsTrait;
use App\Domain\Models\Traits\UuidTrait;
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

#[Entity, Table(name: 'markers'), HasLifecycleCallbacks]
class Marker implements ModelInterface, MediaHostParentInterface
{
    use TimestampsTrait;
    use UuidTrait;

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[
        ManyToOne(targetEntity: Museum::class, inversedBy: "markers"),
        JoinColumn(name: "museum_id", referencedColumnName: "id")
    ]
    private ?Museum $museum;

    #[Column(type: "string", nullable: false)]
    private string $name;

    #[Column(type: "text", nullable: true)]
    private ?string $text;

    #[Column(type: "string", nullable: true)]
    private ?string $title;

    #[OneToOne(targetEntity: MarkerAsset::class, mappedBy: "marker", cascade: ["persist", "remove"])]
    private ?MarkerAsset $asset = null;

    #[Column(type: "boolean", nullable: false)]
    private bool $isActive = true;

    /**
     * One marker has one or many resources. This is the inverse side.
     *
     */
    #[OneToMany(targetEntity: PlacementObject::class, mappedBy: "marker", cascade: ["persist", "remove"])]
    private Collection $resources;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
    }


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
            'dataInfo' => [
                'text' => $this->text,
                'title' => $this->title,
            ],
            'asset' => $this->asset?->getAsset(),
            'resources' => $this->resources->toArray(),
            'isActive' => $this->isActive,
        ];
    }

    /**
     * Get the value of resource.
     *
     * @return Collection<PlacementObject>
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Add a resource variadic set to the collection
     *
     * @param PlacementObject ...$resource
     * @return self
     */
    public function addResources(PlacementObject ...$resource): self
    {
        foreach ($resource as $obj) {
            $obj->setMarker($this);
            $this->resources->add($obj);
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param Collection<PlacementObject> $collection
     * @return self
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
        $resource->setMarker($this);
        $this->resources->add($resource);

        return $this;
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
     * Get the value of text.
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the value of text.
     *
     * @param mixed $text
     *
     * @return self
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the value of title.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title.
     *
     * @param mixed $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get many markers have one museum. This is the owning side.
     */
    public function getMuseum(): ?Museum
    {
        return $this->museum;
    }

    /**
     * Set many markers have one museum. This is the owning side.
     *
     * @param mixed $museum
     *
     * @return self
     */
    public function setMuseum(Museum $museum)
    {
        $this->museum = $museum;

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
     * @param mixed $isActive
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
    public function getAsset(): ?MarkerAsset
    {
        return $this->asset;
    }

    /**
     * Set the value of asset.
     *
     * @return self
     */
    public function setAsset(?MarkerAsset $asset)
    {
        $this->asset = $asset;

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

    public function getMediaAsset(): ?AbstractAsset
    {
        if ($this->asset) {
            return $this->asset->getAsset();
        }
        return null;
    }
}
