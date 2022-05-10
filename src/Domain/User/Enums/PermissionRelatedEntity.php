<?php

declare(strict_types=1);

namespace App\Domain\User\Enums;

enum PermissionRelatedEntity: string
{
    case MACHINE = 'machine';
    case PROJECT = 'project';
    case CLIENT  = 'client';
}
