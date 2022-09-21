<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\UpdateMySelf;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\UseCase\User\AssignmentPermission\AssignmentPermissionHandler;
use App\Domain\User\Entity\ActionUserOnMachine;
use App\Domain\User\Entity\Permission;
use App\Domain\User\Enums\ActionOfUserOnMachine;
use App\Domain\User\Enums\PermissionType;
use App\Domain\User\Repository\ActionUserOnMachineRepositoryInterface;
use App\Domain\User\Repository\PermissionRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

class UpdateMySelfHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PermissionRepositoryInterface $permissionRepository,
        private readonly ActionUserOnMachineRepositoryInterface $actionUserOnMachineRepository,
        private readonly AssignmentPermissionHandler $assignmentPermissionHandler,
    ) {}

    public function __invoke(UpdateMySelfCommand $updateMySelfCommand): void
    {
        $user = $this->userRepository->getByUuid($updateMySelfCommand->loggedUser->getUuid());

        if ($updateMySelfCommand->pubKey !== $user->getPubKey()) {
            /** @var Permission[] $permissions */
            $permissions = $this->permissionRepository->permissionsOfUser($user->getUuid());
            foreach ($permissions as $permission) {
                if ($permission->getUserPermissionType() === PermissionType::SSH) {
                    $machinesLinkedToCurrentPermissions = $this->assignmentPermissionHandler->getMachines(
                        $permission->getRelatedEntity(),
                        $permission->getRelatedEntityUuid()
                    );

                    foreach ($machinesLinkedToCurrentPermissions as $machine) {
                        if ($user->getPubKey()) {
                            $this->actionUserOnMachineRepository->save(
                                new ActionUserOnMachine(
                                    null,
                                    $permission,
                                    $machine,
                                    $user->getPubKey(),
                                    ActionOfUserOnMachine::REMOVE
                                )
                            );
                        }
                        if ($updateMySelfCommand->pubKey) {
                            $this->actionUserOnMachineRepository->save(
                                new ActionUserOnMachine(
                                    null,
                                    $permission,
                                    $machine,
                                    $updateMySelfCommand->pubKey,
                                    ActionOfUserOnMachine::ADD
                                )
                            );
                        }
                    }
                }
            }
        }

        $user->setFirstName($updateMySelfCommand->firstName)
             ->setLastName($updateMySelfCommand->lastName)
             ->setPubKey($updateMySelfCommand->pubKey);

        $this->userRepository->save($user);
    }
}
