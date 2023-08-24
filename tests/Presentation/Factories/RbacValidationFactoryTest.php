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
use App\Presentation\Factories\RbacValidationFactory;
use App\Presentation\Middleware\RoleValidationMiddleware;
use App\Presentation\Protocols\RbacFallbackInterface;
use Middlewares\Utils\RequestHandler;
use Nyholm\Psr7\Response;
use PhpOption\Option;
use PhpOption\Some;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpForbiddenException;
use Tests\TestCase;

class RbacValidationFactoryTest extends TestCase
{
    private RbacValidationFactory $sut;
    private MockObject|AccessControl $accessControl;
    public function setUp(): void
    {
        $roleFetcher = $this->createMock(RoleFetcherInterface::class);
        $resourceFetcher = $this->createMock(ResourceFetcherInterface::class);
        $accessControl = $this->createMock(AccessControl::class);
        $this->autowireContainer(RoleFetcherInterface::class, $roleFetcher);
        $this->autowireContainer(ResourceFetcherInterface::class, $resourceFetcher);
        $this->autowireContainer(AccessControl::class, $accessControl);
        $this->accessControl = $accessControl;

        $this->sut = new RbacValidationFactory($this->getContainer());
    }

    public function testWillRetrieveInstanceWithCorrectValues()
    {
        $validator = $this->sut;
        $subject = $validator('video');
        $this->accessControl->method('getResource')->willReturn(
            new Some(
                new Resource('video', 'description')
            )
        );

        $this->accessControl->expects(self::once())->method('getResource')->with('video');

        $this->assertInstanceOf(RoleValidationMiddleware::class, $subject);
        $this->assertSame('video', $subject->getOptionResource()->get()->name);
    }
}