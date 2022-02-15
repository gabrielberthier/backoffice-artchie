<?php

namespace App\Domain\Models\Assets;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Models\Traits\TimestampsTrait;
use App\Domain\Models\Traits\UuidTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Abstract resource comprehends the entities which hold data such as medias, assets, etc.
 *
 * @ORM\Entity
 * @ORM\Table(name="assets")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *      "3d" = "ThreeDimensionalAsset",
 *      "2d" = "TwoDimensionalAsset"
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
}
