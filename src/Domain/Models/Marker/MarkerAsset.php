<?php

namespace App\Domain\Models\Marker;

use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Traits\TimestampsTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="marker_assets")
 */
class MarkerAsset
{
    use TimestampsTrait;
    /**
     * @ORM\Id @ORM\OneToOne(targetEntity="Marker")
     * @ORM\JoinColumn(name="marker_id", referencedColumnName="id")
     */
    private Marker $marker;

    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="App\Domain\Models\Assets\AbstractAsset")
     * @ORM\JoinColumn(name="marker_id", referencedColumnName="id")
     */
    private AbstractAsset $asset;

    public function __construct(Marker $marker, AbstractAsset $asset)
    {
        $this->asset = $asset;
        $this->marker = $marker;
    }

    /**
     * Get the value of marker.
     */
    public function getMarker(): ?Marker
    {
        return $this->marker;
    }

    /**
     * Get the value of asset.
     */
    public function getAsset(): AbstractAsset
    {
        return $this->asset;
    }

    /**
     * Set the value of marker.
     *
     * @param mixed $marker
     *
     * @return self
     */
    public function setMarker(Marker $marker)
    {
        $this->marker = $marker;

        return $this;
    }

    /**
     * Set the value of asset.
     *
     * @param mixed $asset
     *
     * @return self
     */
    public function setAsset(AbstractAsset $asset)
    {
        $this->asset = $asset;

        return $this;
    }
}
