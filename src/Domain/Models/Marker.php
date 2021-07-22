<?php

declare(strict_types=1);

namespace App\Domain\Models;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="markers")
 */
class Marker implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected int $id;
    private ?string $name;
    private ?string $trackableImage;
    private ?string $text;
    private ?string $title;
    private ?string $url;
    private ResourceModel $resource;

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
    public function setResource($resource)
    {
        $this->resource = $resource;

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
}
