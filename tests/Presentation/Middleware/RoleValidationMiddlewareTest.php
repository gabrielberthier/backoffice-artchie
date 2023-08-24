<?php

declare(strict_types=1);

namespace Tests\Presentation\Middleware;

use App\Data\Protocols\Rbac\ResourceFetcherInterface;
use App\Data\Protocols\Rbac\RoleFetcherInterface;
use App\Domain\Models\RBAC\AccessControl;
use App\Domain\Models\RBAC\ContextIntent;
use App\Domain\Models\RBAC\Permission;
use App\Domain\Models\RBAC\Resource;
use App\Domain\Models\RBAC\Role;
use App\Presentation\Middleware\RoleValidationMiddleware;
use App\Presentation\Protocols\RbacFallbackInterface;
use Middlewares\Utils\RequestHandler;
use Nyholm\Psr7\Response;
use PhpOption\Option;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpForbiddenException;
use Tests\TestCase;

class RoleValidationMiddlewareTest extends TestCase
{
    private \Prophecy\Prophet $prophet;
    private RoleValidationMiddleware $sut;
    private AccessControl $accessControl;
    private MockObject|RoleFetcherInterface $roleFetcher;
    private MockObject|ResourceFetcherInterface $resourceFetcher;

    public function setUp(): void
    {
        $container = $this->getContainer(true);
        $logger = $container->get(LoggerInterface::class);
        $this->accessControl = new AccessControl();
        $roleFetcher = $this->createMock(RoleFetcherInterface::class);
        $resourceFetcher = $this->createMock(ResourceFetcherInterface::class);

        $this->roleFetcher = $roleFetcher;
        $this->resourceFetcher = $resourceFetcher;

        $this->sut = new RoleValidationMiddleware(
            $this->accessControl,
            $roleFetcher,
            $resourceFetcher
        );

        $this->sut->setResourceTarget("video");
    }

    public function testShouldRetrieveRoleIfItExistsInAccessControl()
    {
        $this->accessControl->forgeRole('common', 'description');
        $role = $this->sut->getOptionRole('common')->get();
        $this->assertEquals($role->name, 'common');
        $this->assertInstanceOf(Role::class, $role);
    }

    public function testShouldRetrieveRoleIfItDoesNOTExistInAccessControlButIsAvailableInRoleFetcher()
    {
        $this->roleFetcher->method('getRole')->willReturn(Option::fromValue(new Role('common', 'description')));
        $role = $this->sut->getOptionRole('common')->get();
        $this->assertEquals($role->name, 'common');
        $this->assertInstanceOf(Role::class, $role);
    }

    public function testShouldReturnNothingWhenUnavailableRole()
    {
        $this->roleFetcher->method('getRole')->willReturn(Option::fromValue(null));
        $role = $this->sut->getOptionRole('common');
        $this->assertTrue($role->isEmpty());
    }
    public function testShouldRetrieveResourceIfItExistsInAccessControl()
    {
        $this->accessControl->createResource('video', 'description');
        $resource = $this->sut->getOptionResource()->get();
        $this->assertEquals($resource->name, 'video');
        $this->assertInstanceOf(Resource::class, $resource);
    }

    public function testShouldRetrieveResourceIfItDoesNOTExistInAccessControlButIsAvailableInRoleFetcher()
    {
        $this->resourceFetcher->method('getResource')->willReturn(
            Option::fromValue(new Resource('video', 'description'))
        );
        $resource = $this->sut->getOptionResource()->get();
        $this->assertEquals($resource->name, 'video');
        $this->assertInstanceOf(Resource::class, $resource);
    }

    public function testShouldReturnNothingWhenUnavailableResource()
    {
        $this->resourceFetcher->method('getResource')->willReturn(Option::fromValue(null));
        $resource = $this->sut->getOptionResource();
        $this->assertTrue($resource->isEmpty());
    }

    public function testShouldReceivePredefinedPermissionIfItIsSet()
    {
        $this->sut->setPredefinedPermission(new Permission('file requests', ContextIntent::CUSTOM));
        $permission = $this->sut->getAccessGrantRequest(
            $this->getRequest()
        );
        $this->assertEquals($permission->intent, ContextIntent::CUSTOM);
        $this->assertEquals($permission->name, 'file requests');
    }

    public function testMustAssertPermissionFromRequestMethod()
    {
        $permission = $this->sut->getAccessGrantRequest(
            $this->getRequest()
        );
        $this->assertEquals($permission->intent, ContextIntent::READ);

        $name = "can:" . strtolower(
            ContextIntent::READ->value
        ) . ":" . strtolower('video');

        $this->assertEquals($permission->name, $name);
    }

    public function testShouldThrowWhenNoneRoleAndResource()
    {
        $this->roleFetcher->method('getRole')->willReturn(Option::fromValue(null));
        $this->resourceFetcher->method('getResource')->willReturn(Option::fromValue(null));
        $this->expectException(HttpForbiddenException::class);

        $this->sut->process($this->getRequest(), $this->forgeRequestHandler());
    }

    public function testShouldPassWhenNotAllowedRoleAndResourceButFallbackAvailable()
    {
        $this->accessControl->forgeRole('admin', 'description');
        $this->accessControl->createResource('video', 'description');
        $this->sut->setByPassFallback(new class () implements RbacFallbackInterface {
            public function retry(
                Role|string $role,
                Resource|string $resource,
                ContextIntent|Permission $permission
            ): bool {
                return true;
            }
        });

        $response = $this->sut->process($this->getRequest(), $this->forgeRequestHandler());

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testShouldThrowWhenRoleFromAccessControlHasNOPermissionToAccess()
    {
        $this->accessControl->createResource('video', 'description');
        $this->accessControl->forgeRole('admin', 'description');
        $request = $this->getRequest()->withMethod('POST');

        $this->expectException(HttpForbiddenException::class);

        $this->sut->process($request, $this->forgeRequestHandler());
    }

    public function testShouldThrowWhenRoleFromFetcherHasNOPermissionToAccess()
    {
        $this->accessControl->createResource('video', 'description');
        $this->accessControl->forgeRole('admin', 'description');
        $request = $this->getRequest()->withMethod('POST');

        $role = new Role('admin', 'description');
        $resource = new Resource('video', '');

        $role->addPermissionToResource(
            Permission::makeWithPreferableName(ContextIntent::READ, $resource),
            $resource
        );

        $this->roleFetcher->method('getRole')->willReturn(Option::fromValue($role));

        $this->expectException(HttpForbiddenException::class);

        $this->sut->process($request, $this->forgeRequestHandler());
    }

    # Success cases 👇
    public function testShouldPassWhenRoleFromFetcherHasPermissionToAccess()
    {
        $role = new Role('admin', 'description');
        $resource = new Resource('video', '');

        $this->accessControl->appendResource($resource);
        $role->addPermissionToResource(
            Permission::makeWithPreferableName(ContextIntent::CREATE, $resource),
            $resource
        );

        $this->roleFetcher->method('getRole')->willReturn(Option::fromValue($role));

        $request = $this->getRequest()->withMethod('POST');

        $response = $this->sut->process($request, $this->forgeRequestHandler());

        $this->assertInstanceOf(ResponseInterface::class, $response);

        $body = $response->getBody()->__toString();

        $this->assertSame($body, 'Success');
    }

    # Success cases 👇
    public function testShouldPassWhenRolePresentInAccessControlHasPermissionToAccess()
    {
        $resource = $this->accessControl->createResource('video', 'description');
        $this->accessControl->forgeRole('admin', 'description')->grantAccessOn(
            'admin',
            $resource,
            [Permission::makeWithPreferableName(ContextIntent::CREATE, $resource)]
        );
        $request = $this->getRequest()->withMethod('POST');

        $response = $this->sut->process($request, $this->forgeRequestHandler());

        $this->assertInstanceOf(ResponseInterface::class, $response);

        $body = $response->getBody()->__toString();

        $this->assertSame($body, 'Success');
    }

    private function forgeRequestHandler()
    {
        return new RequestHandler(
            function (ServerRequestInterface $request): ResponseInterface {
                $response = new Response();
                $response->getBody()->write('Success');

                return $response;
            }
        );
    }

    private function getRequest()
    {
        return $this->createRequest(
            'GET',
            '/api/test-auth',
            [
                'HTTP_ACCEPT' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        )->withAttribute('token', [
                    'data' => [
                        'email' => 'mail@mail.com',
                        'username' => 'user123',
                        'role' => 'admin',
                        'authType' => 'artchie',
                        'uuid' => null
                    ]
                ]);
    }
}