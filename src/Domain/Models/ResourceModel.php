<?php

declare(strict_types=1);

namespace App\Domain\Models;

use App\Domain\Models\Traits\TimestampsTrait;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="resources")
 */
class ResourceModel implements JsonSerializable
{
    use TimestampsTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected int $id;
    /**
     * The internal unique identity key.
     *
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidInterface $uuid;
    /**
     * @ORM\Column(type="string")
     */
    private string $name;
    /**
     * @ORM\Column(type="string")
     */
    private string $filename;
    /**
     * @ORM\Column(type="string")
     */
    private string $type;
    /**
     * Many resources have one marker. This is the owning side.
     *
     * @ORM\ManyToOne(targetEntity="Marker", inversedBy="resources")
     * @ORM\JoinColumn(name="marker_id", referencedColumnName="id")
     */
    private ?Marker $marker;

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'filename' => $this->filename,
            'type' => $this->type,
        ];
    }

    /**
     * Get the value of type.
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type.
     *
     * @param mixed $type
     *
     * @return self
     */
    public function setType($type)
    {
        if ('2D' !== $type || '3D' !== $type) {
            throw new InvalidArgumentException('The type of the resource must be 2D or 3D');
        }

        $this->type = $type;

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
     * Get the value of filename.
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the value of filename.
     *
     * @param mixed $filename
     *
     * @return self
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get the value of uuid.
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set the value of uuid.
     *
     * @param mixed $uuid
     *
     * @return self
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

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

    /**
     * Get many resources have one marker. This is the owning side.
     */
    public function getMarker()
    {
        return $this->marker;
    }

    /**
     * Set many resources have one marker. This is the owning side.
     *
     * @param mixed $marker
     *
     * @return self
     */
    public function setMarker($marker)
    {
        $this->marker = $marker;

        return $this;
    }
}
