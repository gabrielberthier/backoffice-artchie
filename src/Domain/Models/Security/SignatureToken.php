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
    private string $publicKey;

    /** @ORM\Column(type="datetime", name="time_to_live") */
    private DateTime $ttl;

    /**
     * One Product has One Shipment.
     *
     * @ORM\OneToOne(targetEntity="App\Domain\Models\Museum")
     */
    private ?Museum $museum;

    public function __construct(
        ?int $id = null,
        string $signature,
        string $publicKey,
    ) {
        $this->id = $id;
        $this->signature = $signature;
        $this->publicKey = $publicKey;
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

    /**
     * Get the value of publicKey.
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Set the value of publicKey.
     *
     * @param mixed $publicKey
     *
     * @return self
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'signature' => $this->getSignature(),
            'publicKey' => $this->getPublicKey(),
        ];
    }
}
