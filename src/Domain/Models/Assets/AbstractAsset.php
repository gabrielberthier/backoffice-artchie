<?php

namespace App\Domain\Models\Assets;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Models\Traits\TimestampsTrait;
use App\Domain\Models\Traits\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * Abstract resource comprehends the entities which hold data such as medias, assets, etc.
 *
 * @ORM\Entity
 * @ORM\Table(name="assets")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="asset_type", type="string")
 * @ORM\DiscriminatorMap({
 *      "3dObject" = "App\Domain\Models\Assets\Types\ThreeDimensionalAsset",
 *      "texture" = "App\Domain\Models\Assets\Types\TextureAsset",
 *      "video" = "App\Domain\Models\Assets\Types\VideoAsset",
 *      "picture" = "App\Domain\Models\Assets\Types\PictureAsset"
 * })
 */
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
    protected ?int $id;
    /** @ORM\Column(type="string", unique=true) */
    private string $path;
    /** @ORM\Column(type="string", unique=true) */
    private string $fileName;
    /** @ORM\Column(type="string", nullable=true) */
    private ?string $url;
    /** @ORM\Column(type="string") */
    private string $mediaType;
    /** @ORM\Column(type="string") */
    private string $originalName;
    /** @ORM\Column(type="string") */
    private string $mimeType;

    private ?string $temporaryLocation = null;

    /**
     * One Asset may have a set of sub assets, e.g., a 3D object can have many textures.
     * 
     * @ORM\OneToMany(targetEntity="AbstractAsset", mappedBy="parent")
     */
    private Collection $children;

    /**
     * Many sub assets have a single parent.
     * 
     * @ORM\ManyToOne(targetEntity="AbstractAsset", inversedBy="children")
     */
    private self $parent;

    public function __construct(string $mediaType)
    {
        if (empty($mediaType)) {
            throw new Exception("Cannot create an asset subtype without expliciting its media type");
        }
        $this->mediaType = $mediaType;
        $this->children = new ArrayCollection();
    }

    abstract public function allowedFormats(): array;

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

        return $this;
    }

    protected function setChildren(Collection $collection)
    {
        $this->children = $collection;
    }
}
