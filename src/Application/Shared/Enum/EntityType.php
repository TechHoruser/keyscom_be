<?php

declare(strict_types=1);

namespace App\Application\Shared\Enum;

enum EntityType: string
{
    case USERS = 'users';
    case CLIENTS = 'clients';
    case PROJECTS = 'projects';
    case MACHINES = 'machines';
}
