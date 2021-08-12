<?php

namespace App\Domain\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="posed_assets")
 */
class PosedAsset extends AbstractAsset
{
    /**
     * @ORM\OneToOne(targetEntity="PlacementObject", mappedBy="asset")
     * @ORM\JoinColumn(name="placement_object_id", referencedColumnName="id")
     */
    private ?PlacementObject $posedObject;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'path' => $this->getPath(),
            'fileName' => $this->getFileName(),
            'url' => $this->getUrl(),
            'mediaType' => $this->getMediaType(),
            'placement_object' => $this->getPosedObject(),
            'created_at' => $this->getCreatedAt(),
            'last_update' => $this->getUpdated(),
        ];
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
}
