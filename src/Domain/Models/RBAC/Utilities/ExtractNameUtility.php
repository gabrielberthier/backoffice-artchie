<?php
namespace App\Domain\Models\RBAC\Utilities;

use App\Domain\Models\RBAC\{Role, Resource, Permission};

class ExtractNameUtility
{
    public static function extractName(Resource|Role|Permission|string $subject): string
    {
        return is_string($subject) ? $subject : $subject->name;
    }
}