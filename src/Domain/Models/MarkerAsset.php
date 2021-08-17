<?php

namespace App\Domain\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="marker_assets")
 */
class MarkerAsset extends AbstractAsset
{
    /**
     * @ORM\OneToOne(targetEntity="Marker", mappedBy="asset")
     * @ORM\JoinColumn(name="marker_id", referencedColumnName="id")
     */
    private ?Marker $marker = null;

    /**
     * Get the value of marker.
     */
    public function getMarker(): ?Marker
    {
        return $this->marker;
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
}
