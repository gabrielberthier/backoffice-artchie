<?php

declare(strict_types=1);

namespace App\Data\Entities\Doctrine;

use App\Domain\Contracts\ModelInterface;
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

    /** @var Collection<DoctrineMarker> */
    #[OneToMany(targetEntity: DoctrineMarker::class, mappedBy: "museum", cascade: ["persist", "remove"])]
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

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMarkers(): Collection
    {
        return $this->markers;
    }

    public function setMarkers(Collection $markers): self
    {
        $this->markers = $markers;

        return $this;
    }

    public function addMarker(DoctrineMarker $marker)
    {
        $this->markers->add($marker);
    }

    /** @return array */
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

    public function getDescription()
    {
        return $this->description;
    }


    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function setInfo(string $info): self
    {
        $this->info = $info;

        return $this;
    }
}
