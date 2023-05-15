<?php

namespace App\Domain\Models\Assets;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Dto\Asset\Command\CreateAsset;
use App\Domain\Models\Traits\TimestampsTrait;
use App\Domain\Models\Traits\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Exception;

/**
 * Abstract resource comprehends the entities which hold data such as medias, assets, etc.
 */
#[
    Entity,
    Table(name: 'markers'),
    HasLifecycleCallbacks,
    InheritanceType("SINGLE_TABLE"),
    DiscriminatorColumn(name: "asset_type", type: "string"),
    DiscriminatorMap([
        "3dObject" => "ThreeDimensionalAsset",
        "texture" => "TextureAsset",
        "video" => "VideoAsset",
        "picture" => "PictureAsset"
    ])
]
abstract class AbstractAsset implements ModelInterface
{
    use TimestampsTrait;
    use UuidTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
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
     * @param Collection<AbstractAsset> $children
     */
    #[OneToMany(targetEntity: AbstractAsset::class, mappedBy: "parent")]
    private Collection $children;

    /**
     * Many sub assets have a single parent.
     */
    #[ManyToOne(targetEntity: AbstractAsset::class, inversedBy: "children")]
    private self $parent;

    public function __construct(string $mediaType)
    {
        if (empty($mediaType)) {
            throw new Exception("Cannot create an asset subtype without expliciting its media type");
        }
        $this->mediaType = $mediaType;
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
    public function setId(int $id)
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
    public function setPath(string $path)
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
    public function setFileName(string $fileName)
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
    public function setUrl(?string $url)
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
    public function setMediaType(string $mediaType)
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
            'mediaType' => $this->mediaType,
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
    public function setTemporaryLocation(?string $temporaryLocation)
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
    public function setOriginalName(string $originalName)
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
    public function setMimeType(string $mimeType)
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
    public function setParent(self $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get one Asset may have a set of sub assets, e.g., a 3D object can have many textures.
     */
    protected function getChildren(): Collection
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

    protected function setChildren(Collection $collection)
    {
        $this->children = $collection;
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
