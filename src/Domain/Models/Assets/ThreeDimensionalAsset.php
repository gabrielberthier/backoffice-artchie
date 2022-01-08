<?php

namespace App\Domain\Models\Assets;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="three_dimensional_assets")
 */
class ThreeDimensionalAsset extends AbstractAsset
{
    /**
     * One 3D Object can have one or more 2D shapes as textures. This is the inverse side.
     *
     * @ORM\OneToMany(targetEntity="TwoDimensionalAsset", mappedBy="model", cascade={"persist", "remove"})
     */
    private Collection $textures;

    public function __construct()
    {
        $this->setMediaType('3dObject');
        $this->textures = new ArrayCollection();
    }

    public function getTextures()
    {
        return $this->textures;
    }

    /**
     * @param mixed $textures
     *
     * @return self
     */
    public function setTextures($textures)
    {
        $this->textures = $textures;

        return $this;
    }

    public function addTexture(TwoDimensionalAsset $texture)
    {
        $this->textures[] = $texture;
        $texture->setModel($this);
    }

    public function jsonSerialize(): array
    {
        return parent::jsonSerialize() + ['textures' => $this->textures];
    }
}
