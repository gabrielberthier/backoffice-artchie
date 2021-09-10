<?php

declare(strict_types=1);

namespace Tests\Domain\UseCases\AsymCrypto;

use App\Data\Protocols\AsymCrypto\SignerInterface;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

class SutTypes
{
    public SignerInterface $service;
}

/**
 * @internal
 * @coversNothing
 */
class SignerTest extends TestCase
{
    use ProphecyTrait;

    private SignerInterface $sut;
}
