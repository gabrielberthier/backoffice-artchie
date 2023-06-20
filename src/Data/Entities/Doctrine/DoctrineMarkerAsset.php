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

#[Entity, Table(name: 'marker_assets'), HasLifecycleCallbacks]
class DoctrineMarkerAsset
{
    use TimestampsTrait;

    #[
        Id,
        OneToOne(targetEntity: DoctrineMarker::class),
        JoinColumn(name: "marker_id", referencedColumnName: "id")
    ]
    private DoctrineMarker $marker;

    #[
        Id,
        ManyToOne(targetEntity: DoctrineAsset::class),
        JoinColumn(name: "asset_id", referencedColumnName: "id")
    ]
    private DoctrineAsset $asset;

    public function __construct(DoctrineMarker $marker, DoctrineAsset $asset)
    {
        $this->asset = $asset;
        $this->marker = $marker;
    }

    /**
     * Get the value of marker.
     */
    public function getMarker(): ?DoctrineMarker
    {
        return $this->marker;
    }

    /**
     * Get the value of asset.
     */
    public function getAsset(): DoctrineAsset
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
    public function setMarker(DoctrineMarker $marker)
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
    public function setAsset(DoctrineAsset $asset)
    {
        $this->asset = $asset;

        return $this;
    }
}
