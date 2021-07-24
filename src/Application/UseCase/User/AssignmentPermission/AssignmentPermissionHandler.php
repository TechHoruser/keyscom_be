<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\AssignmentPermission;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\User\Entity\Permission;
use App\Domain\User\Repository\PermissionRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

class AssignmentPermissionHandler implements CommandHandlerInterface
{
    private PermissionRepositoryInterface $permissionRepository;
    private UserRepositoryInterface $userRepository;

    /**
     * AssignmentPermissionHandler constructor.
     * @param PermissionRepositoryInterface $permissionRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        PermissionRepositoryInterface $permissionRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->permissionRepository = $permissionRepository;
        $this->userRepository = $userRepository;
    }

    public function __invoke(AssignmentPermissionCommand $assignmentPermissionCommand)
    {
        if (is_null($this->permissionRepository->getParentOrSamePermissionOfUser(
            $assignmentPermissionCommand->getUuidOfUserWhoGivesPermissions(),
            Permission::PERMISSION_ADMIN,
            $assignmentPermissionCommand->getTypeRelatedEntity(),
            $assignmentPermissionCommand->getTypeOfMachine(),
            $assignmentPermissionCommand->getRelatedEntityUuid()
        ))) {
            throw new \Exception('You has not permissions for assign this');
        }

        $user = $this->userRepository->getByUuid($assignmentPermissionCommand->getUserUuid());
        if (is_null($user)) {
            throw new \Exception('Not exist the user');
        }

        $this->permissionRepository->save(
            new Permission(
                null,
                $user,
                $assignmentPermissionCommand->getUserPermissionType(),
                $assignmentPermissionCommand->getTypeRelatedEntity(),
                $assignmentPermissionCommand->getTypeOfMachine(),
                $assignmentPermissionCommand->getRelatedEntityUuid()
            )
        );
    }
}
