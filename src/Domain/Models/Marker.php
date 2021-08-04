<?php

declare(strict_types=1);

namespace App\Domain\Models;

use App\Domain\Contracts\ModelInterface;
use App\Domain\Models\Traits\TimestampsTrait;
use Doctrine\Common\Collections\ArrayCollection;
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
    protected int $id;
    /**
     * Many markers have one museum. This is the owning side.
     *
     * @ORM\ManyToOne(targetEntity="Museum", inversedBy="markers")
     * @ORM\JoinColumn(name="museum_id", referencedColumnName="id")
     */
    private ?Museum $museum;
    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    private string $name;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $trackableImage;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $text;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $title;
    /**
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    private ?string $url;
    /**
     * One marker has one or many resources. This is the inverse side.
     *
     * @ORM\OneToMany(targetEntity="ResourceModel", mappedBy="marker", cascade={"persist", "remove"})
     */
    private array $resources;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private bool $isActive = true;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'trackableImage' => $this->trackableImage,
            'dataInfo' => [
                'text' => $this->text,
                'title' => $this->title,
            ],
            'url' => $this->url,
            'resource' => $this->resource,
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
    public function setResources(array | ResourceModel $resource = [])
    {
        if (is_array($resource)) {
            foreach ($resource as $obj) {
                $obj->setMarker($this);
            }
            $this->resources = array_merge($this->resources, $resource);
        } else {
            $this->resources[] = $resource;
            $resource->setMarker($this);
        }

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
     * Get the value of trackableImage.
     */
    public function getTrackableImage()
    {
        return $this->trackableImage;
    }

    /**
     * Set the value of trackableImage.
     *
     * @param mixed $trackableImage
     *
     * @return self
     */
    public function setTrackableImage($trackableImage)
    {
        $this->trackableImage = $trackableImage;

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
     * Get the value of url.
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the value of url.
     *
     * @param mixed $url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

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
}
