<?php


namespace App\Domain\Events\Events;

use App\Domain\Events\Event;
use App\Domain\Models\RBAC\Role;


class OnRoleCreateEvent extends Event
{
    public function __construct(public readonly Role $role)
    {
    }
}