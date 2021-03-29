<?php

declare(strict_types=1);

namespace Tests\Presentation\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Domain\Models\DTO\Credentials;
use App\Presentation\Actions\Protocols\ActionError;
use App\Presentation\Actions\Protocols\ActionPayload;
use function PHPUnit\Framework\assertEquals;
use PHPUnit\Framework\MockObject\MockObject;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class LoginControllerTest extends TestCase
{
    use ProphecyTrait;

    public function createMockRequest(string $email, string $username, string $pass): ServerRequestInterface
    {
        $credentials = new Credentials($email, $username, $pass);
        $request = $this->createRequest('POST', '/auth/login');
        $request->getBody()->write(json_encode($credentials));
        $request->getBody()->rewind();

        return $request;
    }

    public function testShouldCallAuthenticationWithCorrectValues()
    {
        $credentials = new Credentials('any_mail.com', 'username', 'pass');
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $service = $this->createMockService();

        $service->expects($this->once())->method('auth')->with($credentials);

        $container->set(LoginServiceInterface::class, $service);

        $app->handle($this->createMockRequest('any_mail.com', 'username', 'pass'));
    }

    public function testShouldReturn400IfNoUsernameOrEmailIsProvided()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $service = $this->createMockService();

        $container->set(LoginServiceInterface::class, $service);

        $response = $app->handle($this->createMockRequest('email', '', 'pass'));
        $code = $response->getStatusCode();
        assertEquals($code, 400);
    }

    public function shouldReturn400IfValidationReturnsNotNull()
    {
        $app = $this->getAppInstance();
        $this->setUpErrorHandler($app);

        /** @var Container $container */
        $container = $app->getContainer();

        $validatorProphecy = $this->prophesize(Validator::class);
        $validatorProphecy
            ->validate('any_mail.com', 'username', 'pass')
            ->willThrow(new InvalidInputParams())
            ->shouldBeCalledOnce()
        ;

        $container->set(Validator::class, $validatorProphecy->reveal());

        $request = $this->createMockRequest('any_mail.com', 'username', 'pass');

        $response = $app->handle($request);
        $payload = (string) $response->getBody();

        $expectedError = new ActionError(ActionError::BAD_REQUEST, '');
        $expectedPayload = new ActionPayload(statusCode: 400, error: $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testExpectsThreeErrors()
    {
        $app = $this->getAppInstance();

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
