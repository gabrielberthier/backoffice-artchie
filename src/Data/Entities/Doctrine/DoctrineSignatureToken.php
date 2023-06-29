<?php

declare(strict_types=1);

namespace App\Data\Entities\Doctrine;

use App\Data\Entities\Doctrine\Traits\TimestampsTrait;
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

    #[OneToOne(targetEntity: DoctrineMuseum::class)]
    private ?DoctrineMuseum $museum;

    public function __construct(
        ?int $id,
        string $signature,
        string $privateKey,
        ?DoctrineMuseum $museum,
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


    public function setSignature(string $signature): self
    {
        $this->signature = $signature;

        return $this;
    }

    /** @return array */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'signature' => $this->getSignature(),
            'privateKey' => $this->getPrivateKey(),
        ];
    }


    public function getPrivateKey()
    {
        return $this->privateKey;
    }


    public function setPrivateKey(string $privateKey): self
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


    public function setMuseum(DoctrineMuseum $museum): self
    {
        $this->museum = $museum;

        return $this;
    }
}
