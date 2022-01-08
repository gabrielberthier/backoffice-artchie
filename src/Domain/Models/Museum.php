<?php

declare(strict_types=1);

namespace App\Domain\Models;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Models\Marker\Marker;
use App\Domain\Models\Traits\TimestampsTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="museums")
 */
class Museum implements ModelInterface
{
    use TimestampsTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * The internal primary identity key.
     *
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    private string $email;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $info;

    /**
     * One museum has one or many markers. This is the inverse side.
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Models\Marker\Marker", mappedBy="museum", cascade={"persist", "remove"})
     */
    private Collection $markers;

    public function __construct(
        ?int $id = null,
        string $email,
        string $name,
        ?string $description = null,
        ?string $info = null,
        ?UuidInterface $uuid = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->description = $description;
        $this->info = $info;
        $this->uuid = $uuid ?? Uuid::uuid4();
        $this->createdAt = new DateTime();
        $this->updated = new DateTime();
        $this->markers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Get the internal primary identity key.
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set the internal primary identity key.
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
     * Get the value of email.
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email.
     *
     * @param mixed $email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

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

    public function addMarker(Marker $marker)
    {
        $this->markers->add($marker);
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'uuid' => $this->uuid,
            'email' => $this->email,
            'name' => $this->name,
            'info' => $this->info,
            'description' => $this->description,
        ];
    }

    /**
     * Get the value of description.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description.
     *
     * @param mixed $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of info.
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set the value of info.
     *
     * @param mixed $info
     *
     * @return self
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }
}
