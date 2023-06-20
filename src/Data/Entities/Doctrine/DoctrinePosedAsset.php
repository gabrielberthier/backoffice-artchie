<?php

namespace App\Data\Entities\Doctrine;


use App\Data\Entities\Doctrine\DoctrineAsset;
use App\Data\Entities\Doctrine\Traits\TimestampsTrait;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Table;


#[Entity, Table(name: 'posed_assets'), HasLifecycleCallbacks]
class DoctrinePosedAsset
{
    use TimestampsTrait;


    #[
        Id,
        OneToOne(targetEntity: DoctrinePlacementObject::class, mappedBy: "asset"),
        JoinColumn(name: "placement_object_id", referencedColumnName: "id")
    ]
    private DoctrinePlacementObject $posedObject;


    #[
        Id,
        ManyToOne(targetEntity: DoctrineAsset::class),
        JoinColumn(name: "asset_id", referencedColumnName: "id")
    ]
    private DoctrineAsset $asset;

    public function __construct(DoctrinePlacementObject $posedObject, DoctrineAsset $asset)
    {
        $this->posedObject = $posedObject;
        $this->asset = $asset;
    }

    /**
     * Get the value of posedObject.
     */
    public function getPosedObject(): DoctrinePlacementObject
    {
        return $this->posedObject;
    }

    /**
     * Set the value of posedObject.
     *
     * @return self
     */
    public function setPosedObject(DoctrinePlacementObject $posedObject)
    {
        $this->posedObject = $posedObject;

        return $this;
    }

    /**
     * Get the value of asset.
     */
    public function getAsset(): DoctrineAsset
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