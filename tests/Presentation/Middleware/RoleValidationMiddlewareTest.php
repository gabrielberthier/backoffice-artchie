<?php

declare(strict_types=1);

namespace Tests\Presentation\Middleware;

use App\Data\Protocols\Rbac\ResourceFetcherInterface;
use App\Data\Protocols\Rbac\RoleFetcherInterface;
use App\Domain\Dto\AccountDto;
use App\Domain\Models\RBAC\AccessControl;
use App\Domain\Models\RBAC\Resource;
use App\Domain\Models\RBAC\Role;
use App\Infrastructure\Cryptography\BodyTokenCreator;
use App\Infrastructure\Persistence\MemoryRepositories\InMemoryAccountRepository;
use App\Presentation\Middleware\RoleValidationMiddleware;
use Middlewares\Utils\RequestHandler;
use Nyholm\Psr7\Response;
use PhpOption\Option;
use PhpOption\Some;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;


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
            "video",
            $roleFetcher,
            $resourceFetcher
        );
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
}