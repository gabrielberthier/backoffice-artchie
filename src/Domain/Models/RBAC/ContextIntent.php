<?php
namespace App\Domain\Models\RBAC;

enum ContextIntent: string
{
    case CREATE = 'create';
    case READ = 'read';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case CUSTOM = 'custom';
    case FREEPASS = '*';
}