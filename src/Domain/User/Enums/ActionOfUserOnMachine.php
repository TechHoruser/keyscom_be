<?php

declare(strict_types=1);

namespace App\Domain\User\Enums;

enum ActionOfUserOnMachine: string
{
    case ADD = 'add';
    case REMOVE = 'remove';
}
