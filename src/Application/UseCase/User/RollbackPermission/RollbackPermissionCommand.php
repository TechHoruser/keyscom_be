<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\RollbackPermission;

use App\Application\Shared\Command\CommandInterface;

class RollbackPermissionCommand implements CommandInterface
{
    public function __construct(
        readonly string $permissionUuid,
    ) {}
}
