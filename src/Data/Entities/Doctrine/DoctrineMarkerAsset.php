<?php

namespace App\Data\Entities\Doctrine;

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
        OneToOne(targetEntity: DoctrineMarker::class, cascade: ["persist", "remove"]),
        JoinColumn(name: "marker_id", referencedColumnName: "id")
    ]
    private DoctrineMarker $marker;

    #[
        Id,
        ManyToOne(targetEntity: DoctrineAsset::class, cascade: ["persist", "remove"]),
        JoinColumn(name: "asset_id", referencedColumnName: "id")
    ]
    private DoctrineAsset $asset;

    public function __construct(DoctrineMarker $marker, DoctrineAsset $asset)
    {
        $this->asset = $asset;
        $this->marker = $marker;
    }

    public function getMarker(): ?DoctrineMarker
    {
        return $this->marker;
    }

    public function getAsset(): DoctrineAsset
    {
        return $this->asset;
    }

    public function setMarker(DoctrineMarker $marker): self
    {
        $this->marker = $marker;

        return $this;
    }

    public function setAsset(DoctrineAsset $asset): self
    {
        $this->asset = $asset;

        return $this;
    }
}