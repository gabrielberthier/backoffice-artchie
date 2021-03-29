<?php

declare(strict_types=1);

namespace Tests\Presentation\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Domain\Models\DTO\Credentials;
use App\Presentation\Actions\Protocols\ActionError;
use App\Presentation\Actions\Protocols\ActionPayload;
use App\Presentation\Helpers\Validation\ValidationError;
use App\Presentation\Protocols\Validation;
use Exception;
use function PHPUnit\Framework\assertEquals;
use PHPUnit\Framework\MockObject\MockObject;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Tests\Builders\Request\RequestBuilder;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class LoginControllerTest extends TestCase
{
    use ProphecyTrait;
    private App $app;

    protected function setUp(): void
    {
        $this->app = $this->getAppInstance();
        $service = $this->createMockService();
        $this->autowireContainer(LoginServiceInterface::class, $service);
        $validator = $this->createValidatorService();
        $this->autowireContainer(Validation::class, $validator);
    }

    public function testShouldCallAuthenticationWithCorrectValues()
    {
        $credentials = new Credentials('any_mail.com', 'username', 'pass');

        /** @var Container $container */
        $container = $this->getContainer();

        $service = $this->createMockService();

        $service->expects($this->once())->method('auth')->with($credentials);

        $container->set(LoginServiceInterface::class, $service);

        $this->app->handle($this->createMockRequest('any_mail.com', 'username', 'pass'));
    }

    public function testShouldReturn400IfNoUsernameOrEmailIsProvided()
    {
        $app = $this->app;

        /** @var Container $container */
        $container = $app->getContainer();

        $service = $this->createMockService();

        $container->set(LoginServiceInterface::class, $service);

        $response = $app->handle($this->createMockRequest('email', '', 'pass'));
        $code = $response->getStatusCode();
        assertEquals($code, 400);
    }

    public function shouldReturn400IfValidationThrows()
    {
        $app = $this->app;
        $this->setUpErrorHandler($app);

        /** @var Container $container */
        $container = $app->getContainer();

        $validatorProphecy = $this->prophesize(Validator::class);
        $validatorProphecy
            ->validate('any_mail.com', 'username', 'pass')
            ->willReturn(new ValidationError())
            ->shouldBeCalledOnce()
        ;

        $container->set(Validator::class, $validatorProphecy->reveal());

        $request = $this->createMockRequest('any_mail.com', 'username', 'pass');

        $response = $app->handle($request);
        $payload = (string) $response->getBody();

        $expectedError = new ActionError(ActionError::BAD_REQUEST, 'Invalid email');
        $expectedPayload = new ActionPayload(statusCode: 400, error: $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testExpectsThreeErrors()
    {
        $app = $this->app;

        /** @var Container $container */
        $container = $app->getContainer();

        $service = $this->createMockService();

        $container->set(LoginServiceInterface::class, $service);

        $request = $this->constructPostRequest(
            new Credentials('email', 'username', 'pass'),
            'POST',
            '/auth/login'
        );
        /*
         * Internally processes this $request
         * Validate it
         * And maybe throw an error
         */
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ActionError::BAD_REQUEST, 'Email is invalid');
        $expectedPayload = new ActionPayload(statusCode: 400, error: $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    private function createValidatorService()
    {
        return $this->getMockBuilder(Validation::class)
            ->onlyMethods(['validate'])
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    private function getContainer()
    {
        return $this->app->getContainer();
    }

    private function autowireContainer($key, $instance)
    {
        $container = $this->app->getContainer();
        $container->set($key, $instance);
    }

    private function createMockRequest(string $email, string $username, string $pass): ServerRequestInterface
    {
        $credentials = new Credentials($email, $username, $pass);
        $request = $this->createRequest('POST', '/auth/login');
        $request->getBody()->write(json_encode($credentials));
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
        return $this->getMockBuilder(LoginServiceInterface::class)
            ->onlyMethods(['auth'])
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    private function constructPostRequest(
        Credentials $credentials,
        string $method,
        string $path,
        array $headers = null,
        array $serverParams = null,
        array $cookies = null
    ): RequestInterface {
        if ((!$method) || !$path) {
            throw new Exception('Unable to create request');
        }
        $requestBuilder = new RequestBuilder($method, $path);
        if ($headers) {
            $requestBuilder->withHeaders($headers);
        }
        if ($serverParams) {
            $requestBuilder->withServerParam($serverParams);
        }
        if ($cookies) {
            $requestBuilder->withCookies($cookies);
        }

        $request = $requestBuilder->build();
        $request->getBody()->write(json_encode($credentials));
        $request->getBody()->rewind();

        return $request;
    }
}
