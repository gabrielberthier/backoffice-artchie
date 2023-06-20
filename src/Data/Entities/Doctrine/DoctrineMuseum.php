<?php

declare(strict_types=1);

namespace App\Data\Entities\Doctrine;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Models\Marker\Marker;
use App\Data\Entities\Doctrine\Traits\TimestampsTrait;
use App\Data\Entities\Doctrine\Traits\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;


#[Entity, Table(name: 'museums'), HasLifecycleCallbacks]
class DoctrineMuseum implements ModelInterface
{
    use TimestampsTrait;
    use UuidTrait;

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]

    protected $id;

    #[Column(type: 'string', unique: true, nullable: false)]
    private string $email;

    #[Column(type: 'string')]
    private string $name;

    #[Column(type: 'text', nullable: true)]
    private ?string $description;

    #[Column(type: 'string', nullable: true)]
    private ?string $info;

    #[OneToMany(targetEntity: Marker::class, mappedBy: "museum", cascade: ["persist", "remove"])]
    private Collection $markers;

    public function __construct(
        ?int $id,
        string $email,
        string $name,
        ?string $description = null,
        ?string $info = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->description = $description;
        $this->info = $info;
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

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
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