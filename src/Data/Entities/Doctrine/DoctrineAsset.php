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
    protected ?int $id = null;
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
    private ?self $parent = null;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }


    public function getId()
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }


    public function getMediaType(): string
    {
        return $this->mediaType;
    }


    public function setMediaType(string $mediaType): self
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    /** @return array */
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


    public function getTemporaryLocation(): ?string
    {
        return $this->temporaryLocation;
    }
    public function setTemporaryLocation(?string $temporaryLocation): self
    {
        $this->temporaryLocation = $temporaryLocation;

        return $this;
    }


    public function getOriginalName(): string
    {
        return $this->originalName;
    }


    public function setOriginalName(string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }


    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }


    public function setParent(self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

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
