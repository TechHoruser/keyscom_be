<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\AssignmentPermission;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Machine\Repository\MachineRepositoryInterface;
use App\Domain\User\Entity\ActionUserOnMachine;
use App\Domain\User\Entity\Permission;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use App\Domain\User\Repository\ActionUserOnMachineRepositoryInterface;
use App\Domain\User\Repository\PermissionRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Lock\LockFactory;

class AssignmentPermissionHandler implements CommandHandlerInterface
{
    private PermissionRepositoryInterface $permissionRepository;
    private UserRepositoryInterface $userRepository;
    private MachineRepositoryInterface $machineRepository;
    private ActionUserOnMachineRepositoryInterface $actionUserOnMachineRepository;
    private EntityManagerInterface $entityManager;
    private LockFactory $lockFactory;

    public function __construct(
        PermissionRepositoryInterface $permissionRepository,
        UserRepositoryInterface $userRepository,
        MachineRepositoryInterface $machineRepository,
        ActionUserOnMachineRepositoryInterface $actionUserOnMachineRepository,
        EntityManagerInterface $entityManager,
        LockFactory $lockFactory
    ) {
        $this->permissionRepository = $permissionRepository;
        $this->userRepository = $userRepository;
        $this->machineRepository = $machineRepository;
        $this->actionUserOnMachineRepository = $actionUserOnMachineRepository;
        $this->entityManager = $entityManager;
        $this->lockFactory = $lockFactory;
    }

    public function __invoke(AssignmentPermissionCommand $assignmentPermissionCommand)
    {
        if (is_null($this->permissionRepository->getParentOrSamePermissionOfUser(
            $assignmentPermissionCommand->getUuidOfUserWhoGivesPermissions(),
            PermissionType::ADMIN,
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

        try {
            $this->entityManager->getConnection()->beginTransaction();

            $permission = $this->permissionRepository->save(
                new Permission(
                    null,
                    $user,
                    $user,
                    $assignmentPermissionCommand->getUserPermissionType(),
                    $assignmentPermissionCommand->getTypeRelatedEntity(),
                    $assignmentPermissionCommand->getTypeOfMachine(),
                    $assignmentPermissionCommand->getRelatedEntityUuid()
                )
            );

            $lock = $this->lockFactory->createLock($permission->getUuid());
            $lock->acquire(true);

            if ($assignmentPermissionCommand->getUserPermissionType() === PermissionType::SSH) {
                $machinesLinkedToCurrentPermissions = $this->getMachines(
                    $assignmentPermissionCommand->getTypeRelatedEntity(),
                    $assignmentPermissionCommand->getRelatedEntityUuid()
                );

                foreach ($machinesLinkedToCurrentPermissions as $machine) {
                    $this->actionUserOnMachineRepository->save(
                        new ActionUserOnMachine(
                            null,
                            $permission,
                            $machine,
                            ActionUserOnMachine::ACTION_ADD
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
        $filters = [];
        if (!is_null($typeRelatedEntity)) {
            if ($typeRelatedEntity === PermissionRelatedEntity::MACHINE) {
                $filters['uuid'] = $relatedEntityUuid;
            } elseif ($typeRelatedEntity === PermissionRelatedEntity::PROJECT) {
                $filters['project.uuid'] = $relatedEntityUuid;
            } elseif ($typeRelatedEntity === PermissionRelatedEntity::CLIENT) {
                $filters['project.client.uuid'] = $relatedEntityUuid;
            }
        }

        return $this->machineRepository->complexFind(0, 0, null, null, $filters);
    }
}
