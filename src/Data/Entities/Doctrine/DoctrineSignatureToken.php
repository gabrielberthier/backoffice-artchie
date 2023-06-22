<?php

declare(strict_types=1);

namespace App\Domain\Models\Security;

use App\Data\Entities\Doctrine\Traits\TimestampsTrait;
use App\Domain\Models\Museum;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

use DateInterval;
use JsonSerializable;


#[Entity, Table(name: 'signature_tokens'), HasLifecycleCallbacks]
class DoctrineSignatureToken implements JsonSerializable
{
    use TimestampsTrait;

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected $id;

    #[Column(type: 'text', nullable: false)]
    private string $signature;

    #[Column(type: 'text', nullable: false)]
    private string $privateKey;

    #[Column(type: 'datetime', name: 'time_to_live')]
    private DateTimeInterface $ttl;

    #[OneToOne(targetEntity: Museum::class)]
    private ?Museum $museum;

    public function __construct(
        ?int $id,
        string $signature,
        string $privateKey,
        ?Museum $museum,
    ) {
        $this->id = $id;
        $this->signature = $signature;
        $this->privateKey = $privateKey;
        $this->museum = $museum;
        $currentDate = new DateTimeImmutable();
        $this->createdAt = $currentDate;
        $this->updated = $currentDate;
        $this->ttl = $currentDate->add(new DateInterval('P6M'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of signature.
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Set the value of signature.
     *
     * @param mixed $signature
     *
     * @return self
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'signature' => $this->getSignature(),
            'privateKey' => $this->getPrivateKey(),
        ];
    }

    /**
     * Get the value of privateKey.
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Set the value of privateKey.
     *
     * @param mixed $privateKey
     *
     * @return self
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * Get one Product has One Shipment.
     */
    public function getMuseum()
    {
        return $this->museum;
    }

    /**
     * Set one Product has One Shipment.
     *
     * @param mixed $museum
     *
     * @return self
     */
    public function setMuseum($museum)
    {
        $this->museum = $museum;

        return $this;
    }
}
