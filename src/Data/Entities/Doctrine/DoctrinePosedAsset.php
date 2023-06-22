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

#[Entity, Table(name: "posed_assets"), HasLifecycleCallbacks]
class DoctrinePosedAsset
{
    use TimestampsTrait;

    #[
        Id,
        OneToOne(
        targetEntity: DoctrinePlacementObject::class,
        mappedBy: "asset"
    ),
        JoinColumn(name: "placement_object_id", referencedColumnName: "id")
    ]
    private DoctrinePlacementObject $posedObject;

    #[
        Id,
        ManyToOne(targetEntity: DoctrineAsset::class),
        JoinColumn(name: "asset_id", referencedColumnName: "id")
    ]
    private DoctrineAsset $asset;

    public function __construct(
        DoctrinePlacementObject $posedObject,
        DoctrineAsset $asset
    ) {
        $this->posedObject = $posedObject;
        $this->asset = $asset;
    }

    public function getPosedObject(): DoctrinePlacementObject
    {
        return $this->posedObject;
    }

    public function setPosedObject(DoctrinePlacementObject $posedObject): self
    {
        $this->posedObject = $posedObject;

        return $this;
    }

    public function getAsset(): DoctrineAsset
    {
        return $this->asset;
    }

    public function setAsset($asset): self
    {
        $this->asset = $asset;

        return $this;
    }
}