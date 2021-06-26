<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Crypto;

use App\Infrastructure\Cryptography\DataEncryption\Encrypter;
use function PHPUnit\Framework\assertNotSame;
use function PHPUnit\Framework\assertSame;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

/**
 * @internal
 * @coversNothing
 */
class CryptDataTest extends PHPUnit_TestCase
{
    private Encrypter $sut;

    public function setUp(): void
    {
        $this->sut = new Encrypter('hashkey');
    }

    public function testIfEncrypterMakesEncryption()
    {
        $plaintext = 'Ola, pessoal';
        assertNotSame($plaintext, $this->sut->encrypt($plaintext));
    }

    public function testIfEncrypterMakesDecription()
    {
        $plaintext = 'Ola, pessoal';
        $crypted = $this->sut->encrypt($plaintext);

        assertSame($plaintext, $this->sut->decrypt($crypted));
    }
}
