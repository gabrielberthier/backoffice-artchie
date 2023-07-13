<?php

namespace App\Domain\Models\Assets;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Dto\Asset\Command\CreateAsset;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractAsset implements ModelInterface
{
    protected ?int $id = null;
    
    private string $path;
    
    private string $fileName;
    
    private ?string $url;
    
    private string $mediaType;
    
    private string $originalName;
    
    private string $mimeType;
    
    private ?string $temporaryLocation = null;
    
    private ?UuidInterface $uuid = null;
    
    private ?DateTimeInterface $createdAt = null;
    
    private ?DateTimeInterface $updated = null;

    /**
     * One Asset may have a set of sub assets, e.g., a 3D object can have many textures.
     * 
     * @param Collection<AbstractAsset> $children
     */

    private Collection $children;

    private self $parent;

    public function __construct(string $mediaType)
    {
        if ($mediaType === '') {
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
    public function setId(?int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the internal primary identity key.
     */
    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    /**
     * Set the internal primary identity key.
     *
     *
     */
    public function setUuid(UuidInterface|string $uuid): self
    {
        $this->uuid = is_string($uuid) ? Uuid::fromString($uuid) : $uuid;

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

    public function setUpdated(DateTimeInterface $dateTime)
    {
        // WILL be saved in the database
        $this->updated = $dateTime;
    }

    /**
     * Get the value of createdAt.
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updated.
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'path' => $this->path,
            'fileName' => $this->fileName,
            'url' => $this->url,
            'mediaType' => $this->mediaType,
            'created_at' => $this->createdAt,
            'last_update' => $this->updated,
            'mimeType' => $this->mimeType,
            'temporary_location' => $this->temporaryLocation,
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
     * @return Collection<self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * Set one Asset may have a set of sub assets, e.g., a 3D object can have many textures.
     *
     * @return  self
     */
    public function addChild(self $element)
    {
        $this->children->add($element);
        $element->setParent($this);

        return $this;
    }

    public function setChildren(Collection $collection)
    {
        $this->children = $collection;
    }

    public function fromCommand(CreateAsset $createAsset): self
    {
        $this->fileName = $createAsset->fileName;
        $this->path = $createAsset->path;
        $this->url = $createAsset->url;
        $this->originalName = $createAsset->originalName;
        $this->mimeType = $createAsset->mimeType();

        return $this;
    }
}
