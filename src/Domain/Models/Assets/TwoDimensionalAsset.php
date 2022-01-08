<?php

namespace App\Domain\Models\Assets;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="two_dimensional_assets")
 */
class TwoDimensionalAsset extends AbstractAsset
{
    /**
     * Many textures have one model. This is the owning side.
     *
     * @ORM\ManyToOne(targetEntity="ThreeDimensionalAsset", inversedBy="textures")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     */
    private ThreeDimensionalAsset $model;

    public function __construct()
    {
        $this->setMediaType('2dFile');
    }

    /**
     * Get many markers have one museum. This is the owning side.
     */
    public function getModel(): ThreeDimensionalAsset
    {
        return $this->model;
    }

    /**
     * Set many markers have one museum. This is the owning side.
     *
     * @return self
     */
    public function setModel(ThreeDimensionalAsset $model)
    {
        $this->model = $model;

        return $this;
    }
}
