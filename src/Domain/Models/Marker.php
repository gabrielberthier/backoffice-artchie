<?php

declare(strict_types=1);

namespace App\Domain\Models;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Models\Traits\TimestampsTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="markers")
 */
class Marker implements ModelInterface
{
    use TimestampsTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id;
    /**
     * Many markers have one museum. This is the owning side.
     *
     * @ORM\ManyToOne(targetEntity="Museum", inversedBy="markers")
     * @ORM\JoinColumn(name="museum_id", referencedColumnName="id")
     */
    private ?Museum $museum;
    /** @ORM\Column(type="string", unique=true, nullable=false) */
    private string $name;
    /** @ORM\Column(type="string", nullable=true) */
    private ?string $text;
    /** @ORM\Column(type="string", nullable=true) */
    private ?string $title;
    /** @ORM\OneToOne(targetEntity="MarkerAsset", mappedBy="marker") */
    private ?AbstractAsset $asset;

    /** @ORM\Column(type="boolean", nullable=false) */
    private bool $isActive = true;

    /**
     * One marker has one or many resources. This is the inverse side.
     *
     * @ORM\OneToMany(targetEntity="PlacementObject", mappedBy="marker", cascade={"persist", "remove"})
     */
    private Collection $resources;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'dataInfo' => [
                'text' => $this->text,
                'title' => $this->title,
            ],
            'asset' => $this->asset,
            'resources' => $this->resources,
            'museum' => $this->museum,
        ];
    }

    /**
     * Get the value of resource.
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set the value of resource.
     *
     * @param mixed $resource
     *
     * @return self
     */
    public function setResources(PlacementObject ...$resource)
    {
        foreach ($resource as $obj) {
            $obj->setMarker($this);
            $this->resources->add($obj);
        }

        return $this;
    }

    /**
     * Set the value of resource.
     *
     * @return self
     */
    public function addResource(PlacementObject $resource)
    {
        $resource->setMarker($this);
        $this->resources->add($resource);

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
     * Get the value of text.
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the value of text.
     *
     * @param mixed $text
     *
     * @return self
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the value of title.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title.
     *
     * @param mixed $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get many markers have one museum. This is the owning side.
     */
    public function getMuseum(): ?Museum
    {
        return $this->museum;
    }

    /**
     * Set many markers have one museum. This is the owning side.
     *
     * @param mixed $museum
     *
     * @return self
     */
    public function setMuseum(Museum $museum)
    {
        $this->museum = $museum;

        return $this;
    }

    /**
     * Get the value of isActive.
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * Set the value of isActive.
     *
     * @param mixed $isActive
     *
     * @return self
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get the value of asset.
     */
    public function getAsset(): AbstractAsset
    {
        return $this->asset;
    }

    /**
     * Set the value of asset.
     *
     * @return self
     */
    public function setAsset(AbstractAsset $asset)
    {
        $this->asset = $asset;

        return $this;
    }
}
