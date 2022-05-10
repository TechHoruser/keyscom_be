<?php

declare(strict_types=1);

namespace App\Domain\User\Enums;

enum PermissionType: string
{
    case ADMIN = 'admin';
    case SSH   = 'ssh';
}
