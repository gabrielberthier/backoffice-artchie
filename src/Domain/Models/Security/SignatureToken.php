<?php

declare(strict_types=1);

namespace App\Domain\Models\Security;

use App\Domain\Models\Museum;
use App\Domain\Models\Traits\TimestampsTrait;
use DateInterval;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="signature_tokens")
 */
class SignatureToken implements JsonSerializable
{
    use TimestampsTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private string $signature;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private string $privateKey;

    /** @ORM\Column(type="datetime", name="time_to_live") */
    private DateTime $ttl;

    /**
     * One Product has One Shipment.
     *
     * @ORM\OneToOne(targetEntity="App\Domain\Models\Museum")
     */
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
        $this->createdAt = new DateTime();
        $this->updated = new DateTime();
        $this->ttl = $this->createdAt->add(new DateInterval('P6M'));
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

    public function jsonSerialize()
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
