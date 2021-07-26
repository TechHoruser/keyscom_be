<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\AssignmentPermission;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Machine\Entity\Machine;
use App\Domain\Machine\Repository\MachineRepositoryInterface;
use App\Domain\User\Entity\ActionUserOnMachine;
use App\Domain\User\Entity\Permission;
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

    /**
     * AssignmentPermissionHandler constructor.
     * @param PermissionRepositoryInterface $permissionRepository
     * @param UserRepositoryInterface $userRepository
     * @param MachineRepositoryInterface $machineRepository
     * @param ActionUserOnMachineRepositoryInterface $actionUserOnMachineRepository
     * @param EntityManagerInterface $entityManager
     * @param LockFactory $lockFactory
     */
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

            if ($assignmentPermissionCommand->getUserPermissionType() === Permission::PERMISSION_SSH) {
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

    /**
     * @param string|null $typeRelatedEntity
     * @param string|null $relatedEntityUuid
     * @return Machine[]
     */
    private function getMachines(?string $typeRelatedEntity, ?string $relatedEntityUuid): iterable
    {
        $filters = [];
        if (!is_null($typeRelatedEntity)) {
            if ($typeRelatedEntity === 'machine') {
                $filters['uuid'] = $relatedEntityUuid;
            } elseif ($typeRelatedEntity === 'project') {
                $filters['project.uuid'] = $relatedEntityUuid;
            } elseif ($typeRelatedEntity === 'client') {
                $filters['project.client.uuid'] = $relatedEntityUuid;
            }
        }

        return $this->machineRepository->complexFind(0, 0, null, null, $filters);
    }
}
