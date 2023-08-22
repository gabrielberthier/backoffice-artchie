<?php
namespace App\Presentation\Protocols;

use App\Domain\Models\RBAC\{Role, Resource, ContextIntent, Permission};

interface RbacFallbackInterface
{
    public function retry(
        Role|string $role,
        Resource|string $resource,
        ContextIntent|Permission $permission
    ): bool;
}