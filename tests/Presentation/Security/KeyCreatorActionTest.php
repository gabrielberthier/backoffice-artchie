<?php

declare(strict_types=1);

namespace Tests\Presentation\Security;

use App\Data\Protocols\AsymCrypto\SignerInterface;
use App\Presentation\Actions\Protocols\HttpErrors\UnprocessableEntityException;
use App\Presentation\Actions\ResourcesSecurity\KeyCreatorAction;
use PHPUnit\Framework\MockObject\MockObject;
use Prophecy\Prophet;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Slim\Psr7\Response;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class KeyCreatorActionTest extends TestCase
{
    private Prophet $prophet;

    private KeyCreatorAction $sut;

    protected function setUp(): void
    {
        $this->prophet = new Prophet();
        /** @var SignerInterface */
        $service = $this->createMockService();
        $this->sut = new KeyCreatorAction($service);
    }

    public function testIfUuidIsValid()
    {
        $this->expectException(UnprocessableEntityException::class);

        $this->sut->__invoke($this->createRequest('POST', '/'), new Response(), []);
    }

    public function testShouldCallAsymmetricSignerWithCorrectValues()
    {
        $prophecyService = $this->prophet->prophesize(SignerInterface::class);
        $prophecyService->sign(Uuid::fromString('914e4c51-a049-4594-ae5c-921bbadf686b'))->willReturn('')
            ->shouldBeCalledOnce()
        ;
        $action = new KeyCreatorAction($prophecyService->reveal());
        $response = $action($this->createMockRequest(), new Response(), []);
        $payload = (string) $response->getBody();
        $this->assertTrue(is_string($payload));
    }

    public function testShouldReturn200WithCorrectInput()
    {
        $service = $this->createMockService();
        $testString = base64_encode('expectedString');
        $service->expects($this->once())->method('sign')->willReturn($testString);
        /** @var SignerInterface */
        $serviceMocked = $service;
        $action = new KeyCreatorAction($serviceMocked);
        $response = $action->__invoke($this->createMockRequest(), new Response(), []);
        $decoded = json_decode((string) $response->getBody());
        $this->assertSame(200, $decoded->statusCode);
        $this->assertSame($testString, $decoded->data->token);
    }

    private function createMockRequest(): ServerRequestInterface
    {
        $request = $this->createRequest('POST', '/api/forge-credential');

        $request->getBody()
            ->write(
                json_encode(
                    [
                        'uuid' => '914e4c51-a049-4594-ae5c-921bbadf686b'
                    ],
                    JSON_PRETTY_PRINT
                )
            )
        ;

        $request->getBody()->rewind();

        return $request;
    }

    /**
     * Create a mocked login service.
     *
     * @return MockObject
     */
    private function createMockService()
    {
        return $this->getMockBuilder(SignerInterface::class)
            ->onlyMethods(['sign'])
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}