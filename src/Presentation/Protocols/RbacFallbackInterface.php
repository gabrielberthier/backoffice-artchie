<?php
namespace App\Presentation\Protocols;

interface RbacFallbackInterface
{
    public function retry(
        Role|string $role,
        Resource|string $resource,
        ContextIntent|Permission $permission
    ): bool;
}