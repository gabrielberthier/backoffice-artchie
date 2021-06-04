<?php

declare(strict_types=1);

namespace Tests\Presentation\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Domain\Models\DTO\Credentials;
use App\Presentation\Actions\Protocols\ActionError;
use App\Presentation\Actions\Protocols\ActionPayload;
use App\Presentation\Protocols\Validation;
use DI\Container;
use function PHPUnit\Framework\assertEquals;
use PHPUnit\Framework\MockObject\MockObject;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophet;
use Psr\Http\Message\ServerRequestInterface;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class LoginControllerTest extends TestCase
{
    use ProphecyTrait;

    private $prophet;

    protected function setUp(): void
    {
        $this->app = $this->getAppInstance();
        $this->prophet = new Prophet();
        $service = $this->createMockService();
        $this->autowireContainer(LoginServiceInterface::class, $service);
        $validator = $this->createValidatorService();
        $this->autowireContainer(Validation::class, $validator);
    }

    public function testShouldCallAuthenticationWithCorrectValues()
    {
        /** @var Container $container */
        $container = $this->getContainer();
        $service = $this->createMockService();
        $service->expects($this->once())
            ->method('auth')
            ->with($this->makeCredentials())
        ;
        $container->set(LoginServiceInterface::class, $service);
        $this
            ->app
            ->handle($this->createMockRequest('any_mail@gmail.com', 'Password04'))
        ;
    }

    public function testShouldReturn400IfValidationReturnsError()
    {
        $app = $this->app;
        $this->setUpErrorHandler($app);
        $body = new Credentials('mike@gmail.com', 'pass');
        $request = $this->createJsonRequest('POST', '/auth/login', $body);

        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ActionError::UNPROCESSABLE_ENTITY, '[password]: Password wrong my dude');
        $expectedPayload = new ActionPayload(statusCode: 422, error: $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);

        assertEquals($response->getStatusCode(), 422);
    }

    /**
     * @group ignore
     */
    public function testExpectsThreeErrors()
    {
        $app = $this->app;
        $this->setUpErrorHandler($app);
        $request = $this->constructPostRequest(new Credentials('gnberthiermail.com', 'pass'), 'POST', '/auth/login');

        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $payloadDecoded = json_decode($payload);

        $errors = explode("\n", $payloadDecoded
            ->error
            ->description);

        $this->assertEquals(count($errors), 2);
    }

    private function makeCredentials(): Credentials
    {
        return new Credentials('any_mail@gmail.com', 'Password04');
    }

    private function createValidatorService()
    {
        return $this->getMockBuilder(Validation::class)
            ->onlyMethods(['validate'])
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    private function createMockRequest(string $access, string $pass): ServerRequestInterface
    {
        $credentials = new Credentials($access, $pass);
        $request = $this->createRequest('POST', '/auth/login');
        $request->getBody()
            ->write(json_encode($credentials))
        ;
        $request->getBody()
            ->rewind()
        ;

        return $request;
    }

    /**
     * Create a mocked login service.
     *
     * @return MockObject
     */
    private function createMockService()
    {
        return $this->getMockBuilder(LoginServiceInterface::class)
            ->onlyMethods(['auth'])
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}
