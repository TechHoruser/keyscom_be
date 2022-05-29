<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\RevokePermissionsByEntity;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\UseCase\User\RollbackPermission\RollbackPermissionCommand;
use App\Domain\User\Enums\PermissionType;
use App\Domain\User\Repository\PermissionRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

class RevokePermissionsByEntityHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PermissionRepositoryInterface $permissionRepository,
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function __invoke(RevokePermissionsByEntityCommand $revokePermissionsByEntityCommand)
    {
        $user = $this->userRepository->getByUuid($revokePermissionsByEntityCommand->userToRevokePermissionUuid) ??
            throw new \Exception('Not exist the user');

        if (is_null($this->permissionRepository->getParentOrSamePermissionOfUser(
            $revokePermissionsByEntityCommand->loggedUser,
            PermissionType::ADMIN,
            $revokePermissionsByEntityCommand->entity,
            null,
            $revokePermissionsByEntityCommand->entityUuid,
        ))) {
            throw new \Exception('You has not permissions for assign this');
        }

        foreach (array_merge(
            $this->permissionRepository->getSamePermissionOfUser(
                $user,
                $revokePermissionsByEntityCommand->permissionType,
                $revokePermissionsByEntityCommand->entity,
                null,
                $revokePermissionsByEntityCommand->entityUuid,
            ),
            $this->permissionRepository->getChildPermissionsOfUser(
                $user,
                $revokePermissionsByEntityCommand->permissionType,
                $revokePermissionsByEntityCommand->entity,
                null,
                $revokePermissionsByEntityCommand->entityUuid,
            ),
        ) as $permissionToRevoke) {
            $this->commandBus->dispatch(new RollbackPermissionCommand(
                $revokePermissionsByEntityCommand->loggedUser,
                $permissionToRevoke->getUuid(),
            ));
        }
    }
}
