<?php

namespace App\Data\Entities\Doctrine;

use App\Data\Entities\Contracts\ModelCoercionInterface;
use App\Data\Entities\Contracts\ModelParsingInterface;
use App\Data\Entities\Doctrine\Traits\TimestampsTrait;
use App\Data\Entities\Doctrine\Traits\UuidTrait;
use App\Domain\Dto\Asset\Command\CreateAsset;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Assets\Types\AssetFactoryFacade;
use App\Domain\Models\Assets\Types\Helpers\AllowedExtensionChecker;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Assets resources comprehend the entities which hold data such as medias, assets, etc.
 * 
 * @implements ModelCoercionInterface<AbstractAsset>
 * @implements ModelParsingInterface<AbstractAsset>
 */
#[
    Entity,
    Table(name: 'assets'),
    HasLifecycleCallbacks
]
class DoctrineAsset implements ModelCoercionInterface, ModelParsingInterface
{
    use TimestampsTrait;
    use UuidTrait;

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected ?int $id = null;

    #[Column(type: 'string', unique: true)]
    private ?string $path;

    #[Column(type: 'string', unique: true)]
    private ?string $fileName;

    #[Column(type: 'string', nullable: true)]
    private ?string $url;

    #[Column(type: 'string')]
    private ?string $mediaType;

    #[Column(type: 'string')]
    private ?string $originalName;

    #[Column(type: 'string')]
    private ?string $mimeType;

    private ?string $temporaryLocation = null;

    /**
     * One Asset may have a set of sub assets, e.g., a 3D object can have many textures.
     * 
     * @param Collection<static> $children
     */
    #[OneToMany(targetEntity: self::class, mappedBy: "parent")]
    private Collection $children;

    /**
     * Many sub assets have a single parent.
     */
    #[ManyToOne(targetEntity: self::class, inversedBy: "children")]
    private ?self $parent = null;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }


    public function getId()
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }


    public function getMediaType(): string
    {
        return $this->mediaType;
    }


    public function setMediaType(string $mediaType): self
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    /** @return array */
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
            'temporary_location' => $this->getTemporaryLocation(),
        ];
    }


    public function getTemporaryLocation(): ?string
    {
        return $this->temporaryLocation;
    }

    public function setTemporaryLocation(?string $temporaryLocation): self
    {
        $this->temporaryLocation = $temporaryLocation;

        return $this;
    }


    public function getOriginalName(): string
    {
        return $this->originalName;
    }


    public function setOriginalName(string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }


    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }


    public function setParent(self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<DoctrineAsset>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $element)
    {
        $this->children->add($element);
        $element->setParent($this);

        return $this;
    }

    public function setChildren(Collection $collection): self
    {
        $this->children = $collection;

        return $this;
    }

    public function fromCommand(CreateAsset $createAsset)
    {
        $this->fileName = $createAsset->fileName;
        $this->path = $createAsset->path;
        $this->url = $createAsset->url;
        $this->originalName = $createAsset->originalName;
        $this->mimeType = $createAsset->mimeType();
    }

    public function toModel(): AbstractAsset
    {
        $children = $this->getChildren()->map(fn (DoctrineAsset $el) => $el->toModel())->toArray();
        $createAsset = new CreateAsset(
            path: $this->getPath(),
            fileName: $this->getFileName(),
            originalName: $this->getOriginalName(),
            url: $this->getUrl(),
            children: $children,
        );

        $factoryFacade = new AssetFactoryFacade(new AllowedExtensionChecker());
        $asset = $factoryFacade->create($createAsset);
        $asset->setId($this->getId());
        $asset->setUuid($this->getUuid());
        $asset->setCreatedAt($this->getCreatedAt());
        $asset->setUpdated($this->getUpdated());

        return $asset;
    }


    /** @param AbstractAsset $model */
    public function fromModel(object $model): static
    {
        $this
            ->setCreatedAt($model->getCreatedAt())
            ->setFileName($model->getFileName())
            ->setId($model->getId())
            ->setMediaType($model->getMediaType())
            ->setMimeType($model->getMimeType())
            ->setOriginalName($model->getOriginalName())
            ->setPath($model->getPath())
            ->setTemporaryLocation($model->getTemporaryLocation())
            ->setUpdated($model->getUpdated())
            ->setUrl($model->getUrl())
            ->setUuid($model->getUuid());

        if ($this->getParent() instanceof DoctrineAsset) {
            $this->setParent($this->fromModel($model->getParent()));
        }

        foreach ($model->getChildren() as $child) {
            $this->addChild(
                $this->fromModel($child)
            );
        }

        return $this;
    }
}
