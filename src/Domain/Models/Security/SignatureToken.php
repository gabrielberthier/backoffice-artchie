<?php

declare(strict_types=1);

namespace App\Domain\Models\Security;

use App\Domain\Models\Museum;
use App\Domain\Models\Traits\TimestampsTrait;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="accounts")
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
     * @ORM\Column(type="string", nullable=false)
     */
    private string $signature;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $publicKey;

    /**
     * One Product has One Shipment.
     *
     * @ORM\OneToOne(targetEntity="Museum")
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
