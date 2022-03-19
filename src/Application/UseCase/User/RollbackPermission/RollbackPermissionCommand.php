<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\RollbackPermission;

use App\Application\Shared\Command\CommandInterface;
use App\Domain\User\Entity\User;

class RollbackPermissionCommand implements CommandInterface
{
    public function __construct(
        readonly User $loggedUser,
        readonly string $permissionUuid,
    ) {}
}
