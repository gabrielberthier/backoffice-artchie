<?php

declare(strict_types=1);

namespace Tests\Presentation\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Domain\Models\DTO\Credentials;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class LoginControllerTest extends TestCase
{
    use ProphecyTrait;

    /**
     * Create a mocked login service
     *
     * @return MockObject 
     */
    function createMockService()
    {
        $mock = $this->getMockBuilder(LoginServiceInterface::class)
            ->onlyMethods(['auth'])
            ->disableOriginalConstructor()
            ->getMock();
        return $mock;
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

        $request = $this->createRequest('POST', '/auth/login');
        $request->getBody()->write(json_encode($credentials));
        $request->getBody()->rewind();
        $app->handle($request);
    }

    public function testShouldReturn401IfNoUsernameOrEmailIsProvided()
    {
        $credentials = new Credentials('email', '', 'pass');

        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $service = $this->createMockService();

        $container->set(LoginServiceInterface::class, $service);

        $request = $this->createRequest('POST', '/auth/login');
        $request->getBody()->write(json_encode($credentials));
        $request->getBody()->rewind();
        $response = $app->handle($request);
        $code = $response->getStatusCode();
        assertEquals($code, 401);
    }

    public function 
}
