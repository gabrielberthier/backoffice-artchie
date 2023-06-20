<?php

declare(strict_types=1);

namespace App\Domain\Models\PlacementObject;

use App\Data\Protocols\Media\MediaCollectorInterface;
use App\Data\Protocols\Media\MediaHostInterface;
use App\Domain\Contracts\ModelInterface;
use App\Domain\Models\Assets\AbstractAsset;
use App\Domain\Models\Marker\Marker;
use App\Data\Entities\Doctrine\Traits\TimestampsTrait;
use App\Data\Entities\Doctrine\Traits\UuidTrait;
use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

class PlacementObject implements ModelInterface, MediaHostInterface
{
    use TimestampsTrait;
    use UuidTrait;








    public function __construct(
        public ?int $id,
        public string $name,
        public bool $isActive = true,
        public ?Marker $marker,
        public ?PosedAsset $asset = null,
        public DateTimeInterface $createdAt = new DateTimeImmutable(), public DateTimeInterface $updated = new DateTimeImmutable(), public ?UuidInterface $uuid = null)
    {
    }

    public function assetInformation(): ?AbstractAsset
    {
        return $this->asset?->asset;
    }

    public function accept(MediaCollectorInterface $visitor): void
    {
        $visitor->visit($this);
    }

    public function namedBy(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'asset' => $this->asset?->asset,
        ];
    }


    public function getMediaAsset(): ?AbstractAsset
    {
        return $this->asset?->asset;
    }
}