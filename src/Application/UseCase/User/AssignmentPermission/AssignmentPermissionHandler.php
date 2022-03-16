<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\AssignmentPermission;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Machine\Repository\MachineRepositoryInterface;
use App\Domain\User\Entity\ActionUserOnMachine;
use App\Domain\User\Entity\Permission;
use App\Domain\User\Enums\ActionOfUserOnMachine;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use App\Domain\User\Repository\ActionUserOnMachineRepositoryInterface;
use App\Domain\User\Repository\PermissionRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Lock\LockFactory;

class AssignmentPermissionHandler implements CommandHandlerInterface
{
    public function __construct(
        private PermissionRepositoryInterface $permissionRepository,
        private UserRepositoryInterface $userRepository,
        private MachineRepositoryInterface $machineRepository,
        private ActionUserOnMachineRepositoryInterface $actionUserOnMachineRepository,
        private EntityManagerInterface $entityManager,
        private LockFactory $lockFactory,
    ) {}

    public function __invoke(AssignmentPermissionCommand $assignmentPermissionCommand)
    {
        if (is_null($this->permissionRepository->getParentOrSamePermissionOfUser(
            $assignmentPermissionCommand->userUuid,
            $assignmentPermissionCommand->userPermissionType,
            $assignmentPermissionCommand->typeRelatedEntity,
            $assignmentPermissionCommand->typeOfMachine,
            $assignmentPermissionCommand->relatedEntityUuid,
        ))) {
            throw new \Exception('You has not permissions for assign this');
        }

        $user = $this->userRepository->getByUuid($assignmentPermissionCommand->userUuid);
        if (is_null($user)) {
            throw new \Exception('Not exist the user');
        }

        try {
            $this->entityManager->getConnection()->beginTransaction();

            // TODO: Set the correct session user
            $sessionUser = $user;
            $permission = $this->permissionRepository->save(
                new Permission(
                    null,
                    $sessionUser,
                    $user,
                    $assignmentPermissionCommand->userPermissionType,
                    $assignmentPermissionCommand->typeRelatedEntity,
                    $assignmentPermissionCommand->typeOfMachine,
                    $assignmentPermissionCommand->relatedEntityUuid,
                )
            );

            $lock = $this->lockFactory->createLock($permission->getUuid());
            $lock->acquire(true);

            if ($assignmentPermissionCommand->userPermissionType === PermissionType::SSH) {
                $machinesLinkedToCurrentPermissions = $this->getMachines(
                    $assignmentPermissionCommand->typeRelatedEntity,
                    $assignmentPermissionCommand->relatedEntityUuid,
                );

                foreach ($machinesLinkedToCurrentPermissions as $machine) {
                    $this->actionUserOnMachineRepository->save(
                        new ActionUserOnMachine(
                            null,
                            $permission,
                            $machine,
                            ActionOfUserOnMachine::ADD
                        )
                    );
                }
            }

            $this->entityManager->getConnection()->commit();

        } catch (\Throwable $exception) {
            $this->entityManager->getConnection()->rollBack();
            throw $exception;

        } finally {
            $lock->release();
        }
    }

    private function getMachines(?PermissionRelatedEntity $typeRelatedEntity, ?string $relatedEntityUuid): iterable
    {
        if (is_null($typeRelatedEntity)) {
            return $this->machineRepository->complexFind(null);
        }

        $filters = [];
        if ($typeRelatedEntity === PermissionRelatedEntity::MACHINE) {
            $filters['uuid'] = $relatedEntityUuid;
        } elseif ($typeRelatedEntity === PermissionRelatedEntity::PROJECT) {
            $filters['project.uuid'] = $relatedEntityUuid;
        } elseif ($typeRelatedEntity === PermissionRelatedEntity::CLIENT) {
            $filters['project.client.uuid'] = $relatedEntityUuid;
        } else {
            // TODO: Remove generic exception
            throw new \Exception('Unrecognized related entity type.');
        }

        return $this->machineRepository->complexFind(null, null, $filters);
    }
}
