<?php

namespace App\Data\Entities\Doctrine;

use App\Data\Entities\Doctrine\Traits\TimestampsTrait;
use App\Data\Entities\Doctrine\Traits\UuidTrait;
use App\Domain\Contracts\ModelInterface;
use App\Domain\Dto\Asset\Command\CreateAsset;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Aseets resources comprehends the entities which hold data such as medias, assets, etc.
 */
#[
    Entity,
    Table(name: 'assets'),
    HasLifecycleCallbacks
]
class DoctrineAsset implements ModelInterface
{
    use TimestampsTrait;
    use UuidTrait;

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;
    #[Column(type: 'string', unique: true)]
    private string $path;
    #[Column(type: 'string', unique: true)]
    private string $fileName;
    #[Column(type: 'string', nullable: true)]
    private ?string $url;
    #[Column(type: 'string')]
    private string $mediaType;
    #[Column(type: 'string')]
    private string $originalName;
    #[Column(type: 'string')]
    private string $mimeType;

    private ?string $temporaryLocation = null;

    /**
     * One Asset may have a set of sub assets, e.g., a 3D object can have many textures.
     * 
     * @param Collection<static> $children
     */
    #[OneToMany(targetEntity: self::class, mappedBy: "parent")]
    private Collection $children;

    /**
     * Many sub assets have a single parent.
     */
    #[ManyToOne(targetEntity: self::class, inversedBy: "children")]
    private self $parent;

    public function __construct() {
        $this->children = new ArrayCollection();
    }

    /**
     * Get the value of id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id.
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of path.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set the value of path.
     *
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the value of fileName.
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * Set the value of fileName.
     *
     * @return self
     */
    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get the value of url.
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Set the value of url.
     *
     * @return self
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get the value of mediaType.
     */
    public function getMediaType(): string
    {
        return $this->mediaType;
    }

    /**
     * Set the value of mediaType.
     *
     * @param mixed $mediaType
     *
     * @return self
     */
    public function setMediaType(string $mediaType): self
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'uuid' => $this->getUuid(),
            'path' => $this->getPath(),
            'fileName' => $this->getFileName(),
            'url' => $this->getUrl(),
            'mediaType' => $this->getMediaType(),
            'created_at' => $this->getCreatedAt(),
            'last_update' => $this->getUpdated(),
            'mimeType' => $this->getMimeType(),
            'temporary_location' => $this->getTemporaryLocation(),
        ];
    }

    /**
     * Get the value of temporaryLocation.
     */
    public function getTemporaryLocation(): ?string
    {
        return $this->temporaryLocation;
    }

    /**
     * Set the value of temporaryLocation.
     *
     * @param mixed $temporaryLocation
     *
     * @return self
     */
    public function setTemporaryLocation(?string $temporaryLocation): self
    {
        $this->temporaryLocation = $temporaryLocation;

        return $this;
    }

    /**
     * Get the value of originalName.
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * Set the value of originalName.
     *
     * @param mixed $originalName
     *
     * @return self
     */
    public function setOriginalName(string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get the value of mimeType.
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Set the value of mimeType.
     *
     * @return self
     */
    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get many sub assets have a single parent.
     */
    public function getParent(): self
    {
        return $this->parent;
    }

    /**
     * Set many sub assets have a single parent.
     *
     * @return  self
     */
    public function setParent(self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get one Asset may have a set of sub assets, e.g., a 3D object can have many textures.
     * 
     * @var Collection<DoctrineAsset>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * Set one Asset may have a set of sub assets, e.g., a 3D object can have many textures.
     *
     * @param self $element
     * @return  self
     */
    protected function addChild(self $element)
    {
        $this->children->add($element);
        $element->setParent($this);

        return $this;
    }

    public function setChildren(Collection $collection): self
    {
        $this->children = $collection;

        return $this;
    }

    public function fromCommand(CreateAsset $createAsset)
    {
        $this->fileName = $createAsset->fileName;
        $this->path = $createAsset->path;
        $this->url = $createAsset->url;
        $this->originalName = $createAsset->originalName;
        $this->mimeType = $createAsset->mimeType();
    }
}