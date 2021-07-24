<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\AssignmentPermission;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\User\Repository\PermissionRepositoryInterface;

class AssignmentPermissionHandler implements CommandHandlerInterface
{
    private PermissionRepositoryInterface $permissionRepository;

    public function __construct(
        PermissionRepositoryInterface $permissionRepository
    ) {
        $this->permissionRepository = $permissionRepository;
    }

    public function __invoke(AssignmentPermissionCommand $assignmentPermissionCommand)
    {
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $assignmentPermissionCommand->getUserUuid(),
            $assignmentPermissionCommand->getUserPermissionType(),
            $assignmentPermissionCommand->getRelatedEntity(),
            $assignmentPermissionCommand->getTypeOfMachine(),
            $assignmentPermissionCommand->getRelatedEntityUuid()
        );

        return $results;
    }
}
