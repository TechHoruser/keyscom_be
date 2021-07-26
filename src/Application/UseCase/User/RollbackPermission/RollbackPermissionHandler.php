<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\RollbackPermission;

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

class RollbackPermissionHandler implements CommandHandlerInterface
{
    private PermissionRepositoryInterface $permissionRepository;
    private UserRepositoryInterface $userRepository;
    private MachineRepositoryInterface $machineRepository;
    private ActionUserOnMachineRepositoryInterface $actionUserOnMachineRepository;
    private EntityManagerInterface $entityManager;
    private LockFactory $lockFactory;

    /**
     * RollbackPermissionHandler constructor.
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

    public function __invoke(RollbackPermissionCommand $rollbackPermissionCommand)
    {
        $permission = $this->permissionRepository->getByUuid($rollbackPermissionCommand->getPermissionUuid());
        if (is_null($permission)) {
            throw new \Exception('Not exist the permission');
        }

        $user = $this->userRepository->getByUuid($rollbackPermissionCommand->getUuidOfUserWhoRevokePermissions());
        if (is_null($user)) {
            throw new \Exception('Not exist the user');
        }

        if (is_null($this->permissionRepository->getParentOrSamePermissionOfUser(
            $rollbackPermissionCommand->getUuidOfUserWhoRevokePermissions(),
            Permission::PERMISSION_ADMIN,
            $permission->getRelatedEntity(),
            $permission->getTypeOfMachine(),
            $permission->getRelatedEntityUuid()
        ))) {
            throw new \Exception('You has not permissions for assign this');
        }

        $lock = $this->lockFactory->createLock($permission->getUuid());
        $lock->acquire(true);

        try {
            $this->entityManager->getConnection()->beginTransaction();

            $actions = $permission->getActions();
            foreach ($actions as $action) {
                if ($action->isProcessed()) {
                    $this->actionUserOnMachineRepository->save(new ActionUserOnMachine(
                        null,
                        $action->getPermission(),
                        $action->getMachine(),
                        $action->getActionToDo() === ActionUserOnMachine::ACTION_ADD ?
                            ActionUserOnMachine::ACTION_REMOVE :
                            ActionUserOnMachine::ACTION_ADD
                    ));
                } else {
                    $action->setCanceled(true);
                    $this->actionUserOnMachineRepository->save($action);
                }
            }
            $permission->setReverted(true);
            $this->permissionRepository->save($permission);

            $this->entityManager->getConnection()->commit();

        } catch (\Throwable $exception) {
            $this->entityManager->getConnection()->rollBack();
            throw $exception;

        } finally {
            $lock->release();
        }
    }
}
