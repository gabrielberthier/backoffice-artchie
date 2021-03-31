<?php

declare(strict_types=1);

namespace Tests\Presentation\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Domain\Models\DTO\Credentials;
use App\Presentation\Actions\Auth\LoginController;
use App\Presentation\Actions\Protocols\ActionError;
use App\Presentation\Actions\Protocols\ActionPayload;
use App\Presentation\Helpers\Validation\ValidationError;
use App\Presentation\Protocols\Validation;
use DI\Container;
use Exception;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use PHPUnit\Framework\MockObject\MockObject;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophet;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Response;
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
        $validator->expects($this->once())->method('validate')->willReturn(null);
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

    public function testShouldReturn400IfValidationReturnsError()
    {
        $app = $this->app;

        $this->expectException(HttpBadRequestException::class);

        /** @var Container $container */
        $container = $app->getContainer();

        $body = new Credentials('email', 'username', 'pass');

        $request = $this->createJsonRequest('POST', '/auth/login', $body);

        $errors = new ValidationError('Message', 400);
        $validator = $this->getMockBuilder(Validation::class)->disableOriginalConstructor()->getMock();
        $validator->expects($this->once())
                    ->method('validate')
                    ->willReturn($this->returnValue($errors));

        $loginController = new LoginController($container->get(LoginServiceInterface::class), $validator);

        $return = $loginController($request, new Response(), []);
        assertNotNull($return);
    }

    /**
     * @group ignore
     */
    public function testExpectsThreeErrors()
    {
        $this->markTestSkipped();
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
            ->getMock();
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
            ->getMock();
    }
}
