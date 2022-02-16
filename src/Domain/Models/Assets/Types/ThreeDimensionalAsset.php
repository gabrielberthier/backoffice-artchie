<?php

namespace App\Domain\Models\Assets\Types;

use App\Domain\Models\Assets\AbstractAsset;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="three_dimensional_assets")
 */
class ThreeDimensionalAsset extends AbstractAsset
{
    public function __construct()
    {
        parent::__construct('3dObject');
    }

    public function getTextures()
    {
        return $this->getChildren();
    }

    /**
     * @param mixed $textures
     *
     * @return self
     */
    public function setTextures(Collection $children)
    {
        $this->setChildren($children);

        return $this;
    }

    public function addTexture(TextureAsset $texture)
    {
        $this->addChild($texture);
        $texture->setParent($this);
    }

    public function jsonSerialize(): mixed
    {
        return parent::jsonSerialize() + ['textures' => $this->getChildren()];
    }

    function allowedFormats(): array
    {
        return [
            'obj', 'fbx', 'dae', '3ds', 'dxf'
        ];
    }
}
