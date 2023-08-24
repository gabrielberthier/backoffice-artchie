<?php

namespace App\Presentation\Middleware;

use App\Data\Protocols\Rbac\ResourceFetcherInterface;
use App\Data\Protocols\Rbac\RoleFetcherInterface;
use App\Domain\Models\RBAC\AccessControl;
use App\Domain\Models\RBAC\ContextIntent;
use App\Domain\Models\RBAC\Permission;
use App\Domain\Models\RBAC\Resource;
use App\Domain\Models\RBAC\Role;
use App\Domain\Models\Token;
use PhpOption\LazyOption;
use PhpOption\Option;
use App\Presentation\Protocols\RbacFallbackInterface;
use Closure;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpForbiddenException;

class RoleValidationMiddleware implements Middleware
{
    private ?RbacFallbackInterface $bypassFallback = null;
    private ?Permission $predefinedPermission = null;
    private Resource|string $resource;

    public function __construct(
        public readonly AccessControl $accessControl,
        public readonly RoleFetcherInterface $roleFetcher,
        public readonly ResourceFetcherInterface $resourceFetcher
    ) {
    }
    public function process(Request $request, RequestHandler $handler): Response
    {
        /** @var array */
        $rawToken = $request->getAttribute("token");
        $token = new Token(...$rawToken["data"]);

        $permission = $this->getAccessGrantRequest($request);
        $maybeRole = $this->getOptionRole($token->role);
        $maybeResource = $this->getOptionResource();

        if ($maybeRole->isDefined() && $maybeResource->isDefined()) {
            $role = $maybeRole->get();
            $resource = $maybeResource->get();

            $canAccess = $this->accessControl->tryAccess(
                $role,
                $resource,
                $permission,
                $this->getFallback()
            );

            if ($canAccess) {
                return $handler->handle($request);
            }
        }

        throw new HttpForbiddenException($request, "Access forbidden");
    }

    public function setResourceTarget(Resource|string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function setByPassFallback(RbacFallbackInterface $fallback): self
    {
        $this->bypassFallback = $fallback;

        return $this;
    }

    public function setPredefinedPermission(Permission $permission): self
    {
        $this->predefinedPermission = $permission;

        return $this;
    }

    public function getAccessGrantRequest(Request $request): Permission
    {
        return
            $this->predefinedPermission ??
            $this->makeGrantBasedOnRequestMethod($request->getMethod());
    }

    /**
     * Will fetch a role from access control instance, and case it does not find it
     * will use a fallback method to fetch from another mean using an implementation
     * of RoleFetcherInterface. 
     * 
     * @return Option<Role>
     */
    public function getOptionRole(string $role): Option
    {
        return $this->accessControl
            ->getRole($role)
            ->orElse(
                new LazyOption(
                    fn(): Option => $this->roleFallback($role)
                )
            );
    }

    /**
     * Will fetch a resource from the access control instance, 
     * and in case it does not find it
     * will use a fallback method to fetch from another mean using an implementation
     * of ResourceFetcherInterface. 
     * 
     * @return Option<Resource>
     */
    public function getOptionResource(): Option
    {
        return $this->accessControl
            ->getResource($this->resource)
            ->orElse(
                new LazyOption(
                    $this->resourceFallback(...)
                )
            );
    }

    /**
     * Creates a Permission object based on request method.
     */
    private function makeGrantBasedOnRequestMethod(
        string $method
    ): Permission {
        $contextIntent = match (strtoupper($method)) {
            "GET" => ContextIntent::READ,
            "POST" => ContextIntent::CREATE,
            "PATCH", "PUT" => ContextIntent::UPDATE,
            "DELETE" => ContextIntent::DELETE,
        };

        return Permission::makeWithPreferableName(
            $contextIntent,
            $this->resource
        );
    }

    /** @return Option<Role> */
    private function roleFallback(string $role): Option
    {
        $returned = $this->roleFetcher
            ->getRole($role)
            ->map(
                function (Role $role): Role {
                    $this->accessControl->appendRole($role);

                    return $role;
                }
            );

        return $returned;
    }

    /** @return Option<Resource> */
    private function resourceFallback(): Option
    {
        return $this->resourceFetcher
            ->getResource($this->resource)
            ->map(
                function (Resource $resource): Resource {
                    $this->accessControl->appendResource($resource);

                    return $resource;
                }
            );
    }

    private function getFallback(): ?Closure
    {
        if (is_null($this->bypassFallback)) {
            return null;
        }

        return $this->bypassFallback->retry(...);
    }
}