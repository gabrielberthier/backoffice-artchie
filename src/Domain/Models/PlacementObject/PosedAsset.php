<?php

namespace App\Domain\Models\PlacementObject;

use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Traits\TimestampsTrait;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Table;


#[Entity, Table(name: 'posed_assets'), HasLifecycleCallbacks]
class PosedAsset
{
    use TimestampsTrait;


    #[
        Id,
        OneToOne(targetEntity: PlacementObject::class, mappedBy: "asset"),
        JoinColumn(name: "placement_object_id", referencedColumnName: "id")
    ]
    private PlacementObject $posedObject;


    #[
        Id,
        ManyToOne(targetEntity: AbstractAsset::class),
        JoinColumn(name: "asset_id", referencedColumnName: "id")
    ]
    private AbstractAsset $asset;

    public function __construct(PlacementObject $posedObject, AbstractAsset $asset)
    {
        $this->posedObject = $posedObject;
        $this->asset = $asset;
    }

    /**
     * Get the value of posedObject.
     */
    public function getPosedObject(): PlacementObject
    {
        return $this->posedObject;
    }

    /**
     * Set the value of posedObject.
     *
     * @return self
     */
    public function setPosedObject(PlacementObject $posedObject)
    {
        $this->posedObject = $posedObject;

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
     * @param mixed $asset
     *
     * @return self
     */
    public function setAsset($asset)
    {
        $this->asset = $asset;

        return $this;
    }
}
