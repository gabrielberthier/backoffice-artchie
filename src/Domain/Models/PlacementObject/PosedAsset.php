<?php

namespace App\Domain\Models\PlacementObject;

use App\Domain\Models\Assets\AbstractAsset;
use DateTimeImmutable;
use DateTimeInterface;



readonly class PosedAsset
{
    public function __construct(
        public PlacementObject $posedObject,
        public AbstractAsset $asset,
        public DateTimeInterface $createdAt = new DateTimeImmutable(), 
        public DateTimeInterface $updated = new DateTimeImmutable())
    {
    }
}