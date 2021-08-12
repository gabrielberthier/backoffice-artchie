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
    private ?Marker $marker;

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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'path' => $this->getPath(),
            'fileName' => $this->getFileName(),
            'url' => $this->getUrl(),
            'mediaType' => $this->getMediaType(),
            'marker' => $this->getMarker(),
            'created_at' => $this->getCreatedAt(),
            'last_update' => $this->getUpdated(),
        ];
    }
}
