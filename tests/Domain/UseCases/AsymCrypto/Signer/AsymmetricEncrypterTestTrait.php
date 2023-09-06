<?php

namespace Tests\Domain\UseCases\AsymCrypto\Signer;

use App\Data\Protocols\Cryptography\AsymmetricEncrypter;
use App\Domain\Dto\Signature;
use App\Domain\Models\Museum;
use Ramsey\Uuid\Uuid;

trait AsymmetricEncrypterTestTrait
{
    public function testIfAsymmetricEncrypterReceivesValues()
    {
        /**
         * @var \PHPUnit\Framework\MockObject\MockObject
         */
        $encrypter = $this->sut->encrypter;
        /** @var \PHPUnit\Framework\MockObject\MockObject */
        $repo = $this->sut->repository;
        $uuid = Uuid::fromString('5a4bd710-aab8-4ebc-b65d-0c059a960cfb');
        $museum = new Museum(2, email: '', name: 'test_museum', uuid: $uuid);
        $repo->method('findByUUID')->willReturn($museum);

        $subject = json_encode(
            [
                'uuid' => '5a4bd710-aab8-4ebc-b65d-0c059a960cfb',
                'museum_name' => 'test_museum',
            ]
        );

        $encrypter->expects($this->once())->method('encrypt')->with($subject)->willReturn(new Signature('privKey', 'pubKey', 'signature'));

        $response = $this->sut->signer->sign($uuid);

        $this->assertTrue(is_string($response));
    }

    public function testIfSignerReturnsValid64BasedString()
    {
        /**
         * @var \PHPUnit\Framework\MockObject\MockObject
         */
        $encrypter = $this->sut->encrypter;
        /** @var \PHPUnit\Framework\MockObject\MockObject */
        $repo = $this->sut->repository;
        $uuid = Uuid::fromString('5a4bd710-aab8-4ebc-b65d-0c059a960cfb');
        $museum = new Museum(2, email: '', name: 'test_museum', uuid: $uuid);
        $repo->method('findByUUID')->willReturn($museum);

        $subject = json_encode(
            [
                'uuid' => '5a4bd710-aab8-4ebc-b65d-0c059a960cfb',
                'museum_name' => 'test_museum',
            ]
        );

        $encodedUuid = base64_encode('5a4bd710-aab8-4ebc-b65d-0c059a960cfb');
        $encodedPrivateKey = base64_encode('pubKey');

        $payload = "{$encodedUuid}.{$encodedPrivateKey}";

        $encrypter->expects($this->once())->method('encrypt')->with($subject)->willReturn(new Signature('privKey', 'pubKey', 'test'));

        $response = $this->sut->signer->sign($uuid);

        list($uuid, $privateKey) = explode('.', $response);

        $this->assertSame(base64_decode($uuid, true), '5a4bd710-aab8-4ebc-b65d-0c059a960cfb');
        $this->assertSame(base64_decode($privateKey, true), 'pubKey');
        $this->assertSame($payload, $response);
    }

    /**
     * Create a mocked login service.
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function createEncrypterMock()
    {
        return $this->getMockBuilder(AsymmetricEncrypter::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}