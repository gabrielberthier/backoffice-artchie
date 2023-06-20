<?php

namespace App\Domain\Models\Marker;

use App\Domain\Models\Assets\AbstractAsset;
use DateTimeImmutable;
use DateTimeInterface;

readonly class MarkerAsset
{

    public function __construct(
        public Marker $marker,
        public AbstractAsset $asset,
        public DateTimeInterface $createdAt = new DateTimeImmutable(), 
        public DateTimeInterface $updated = new DateTimeImmutable())
    {

    }
}