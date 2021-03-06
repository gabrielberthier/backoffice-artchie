<?php

namespace App\Domain\Models;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Models\Traits\TimestampsTrait;
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
 *      "marker" = "MarkerAsset",
 *      "posed" = "PosedAsset"
 * })
 */
abstract class AbstractAsset implements ModelInterface
{
    use TimestampsTrait;

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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'path' => $this->getPath(),
            'fileName' => $this->getFileName(),
            'url' => $this->getUrl(),
            'mediaType' => $this->getMediaType(),
            'created_at' => $this->getCreatedAt(),
            'last_update' => $this->getUpdated(),
        ];
    }
}
