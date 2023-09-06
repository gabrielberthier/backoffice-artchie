<?php

declare(strict_types=1);

namespace App\Data\Entities\Doctrine;

use App\Data\Entities\Contracts\ModelCoercionInterface;
use App\Data\Entities\Contracts\ModelParsingInterface;
use App\Data\Entities\Doctrine\Traits\TimestampsTrait;
use App\Data\Entities\Doctrine\Traits\UuidTrait;
use App\Domain\Models\Museum;
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
class DoctrineMuseum implements ModelCoercionInterface, ModelParsingInterface
{
    use TimestampsTrait;
    use UuidTrait;

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected $id;

    #[Column(type: 'string', unique: true, nullable: false)]
    private ?string $email;

    #[Column(type: 'string')]
    private ?string $name;

    #[Column(type: 'text', nullable: true)]
    private ?string $description;

    #[Column(type: 'string', nullable: true)]
    private ?string $info;

    /** @var Collection<DoctrineMarker> */
    #[OneToMany(targetEntity: DoctrineMarker::class, mappedBy: "museum", cascade: ["persist", "remove"])]
    private Collection $markers;

    public function __construct()
    {
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

    function toModel(): Museum
    {
        $markers = $this
            ->getMarkers()
            ->map(
                static fn (DoctrineMarker $doctrineMarker) => $doctrineMarker->toModel()
            )->toArray();

        return new Museum(
            id: $this->id,
            email: $this->email,
            name: $this->name,
            description: $this->description,
            info: $this->info,
            markers: $markers,
            uuid: $this->uuid,
            createdAt: $this->createdAt,
            updated: $this->updated,
        );
    }


    /** @param Museum $model */
    public function fromModel(object $model): static
    {
        $this->id = $model->id;
        $this->email = $model->email;
        $this->name = $model->name;
        $this->description = $model->description;
        $this->info = $model->info;


        foreach ($model->markers as $marker) {
            $doctrineMarker = new DoctrineMarker();
            $this->addMarker(
                $doctrineMarker->fromModel($marker)
            );
        }

        return $this;
    }
}
