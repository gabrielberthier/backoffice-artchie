<?php
namespace App\Domain\Models\Enums;

enum AuthTypes: string
{
    case GOOGLE = 'google';
    case CUSTOM = 'custom';
}