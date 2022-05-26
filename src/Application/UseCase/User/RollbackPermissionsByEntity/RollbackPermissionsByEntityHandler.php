<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\RollbackPermissionsByEntity;

use App\Application\Shared\Command\CommandHandlerInterface;

class RollbackPermissionsByEntityHandler implements CommandHandlerInterface
{
    public function __construct(
    ) {}

    public function __invoke(RollbackPermissionsByEntityCommand $rollbackPermissionByEntityCommand)
    {
    }
}
