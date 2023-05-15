<?php

namespace App\Domain\Models\Marker;

use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Traits\TimestampsTrait;

use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'marker_assets'), HasLifecycleCallbacks]
class MarkerAsset
{
    use TimestampsTrait;

    #[
        Id,
        OneToOne(targetEntity: Marker::class),
        JoinColumn(name: "marker_id", referencedColumnName: "id")
    ]
    private Marker $marker;

    #[
        Id,
        ManyToOne(targetEntity: AbstractAsset::class),
        JoinColumn(name: "asset_id", referencedColumnName: "id")
    ]
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
