<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\AssignmentPermission;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Machine\Repository\MachineRepositoryInterface;
use App\Domain\Project\Repository\ProjectRepositoryInterface;
use App\Domain\Shared\Entities\PaginationProperties;
use App\Domain\Shared\Errors\DomainError;
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
        private readonly PermissionRepositoryInterface $permissionRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly MachineRepositoryInterface $machineRepository,
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly ClientRepositoryInterface $clientRepository,
        private readonly ActionUserOnMachineRepositoryInterface $actionUserOnMachineRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly LockFactory $lockFactory,
    ) {}

    public function __invoke(AssignmentPermissionCommand $assignmentPermissionCommand)
    {
        $user = $this->userRepository->getByUuid($assignmentPermissionCommand->userUuid) ??
            throw new DomainError('Not exist the user');

        if (empty($this->permissionRepository->getParentOrSamePermissionOfUser(
            $assignmentPermissionCommand->loggedUser,
            PermissionType::ADMIN,
            $assignmentPermissionCommand->typeRelatedEntity,
            null,
            $assignmentPermissionCommand->relatedEntityUuid,
        ))) {
            throw new DomainError('You has not permissions for assign this');
        }

        if ($typeRelatedEntity = $assignmentPermissionCommand->typeRelatedEntity?->value) {
            $repositoryByRelatedEntity = [
                PermissionRelatedEntity::CLIENT->value  => $this->clientRepository,
                PermissionRelatedEntity::PROJECT->value => $this->projectRepository,
                PermissionRelatedEntity::MACHINE->value => $this->machineRepository,
            ];

            $repositoryByRelatedEntity[$typeRelatedEntity]->getByUuid($assignmentPermissionCommand->relatedEntityUuid)
                ?? throw new DomainError('Not exist entity.');
        }

        try {
            $this->entityManager->getConnection()->beginTransaction();

            $permission = $this->permissionRepository->save(
                new Permission(
                    null,
                    $this->userRepository->getByUuid($assignmentPermissionCommand->loggedUser->getUuid()),
                    $user,
                    $assignmentPermissionCommand->userPermissionType,
                    $assignmentPermissionCommand->typeRelatedEntity,
                    $assignmentPermissionCommand->typeOfMachine,
                    $assignmentPermissionCommand->relatedEntityUuid,
                )
            );

            if ($user->getPubKey()) {

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
                                $user->getPubKey(),
                                ActionOfUserOnMachine::ADD
                            )
                        );
                    }
                }
            }

            $this->entityManager->getConnection()->commit();

        } catch (\Throwable $exception) {
            $this->entityManager->getConnection()->rollBack();
            throw $exception;

        } finally {
            if (isset($lock)) {
                $lock->release();
            }
        }
    }

    public function getMachines(?PermissionRelatedEntity $typeRelatedEntity, ?string $relatedEntityUuid): iterable
    {
        if (is_null($typeRelatedEntity)) {
            return $this->machineRepository->complexFind();
        }

        $filters = [];
        if ($typeRelatedEntity === PermissionRelatedEntity::MACHINE) {
            $filters['uuid'] = $relatedEntityUuid;
        } elseif ($typeRelatedEntity === PermissionRelatedEntity::PROJECT) {
            $filters['project.uuid'] = $relatedEntityUuid;
        } elseif ($typeRelatedEntity === PermissionRelatedEntity::CLIENT) {
            $filters['project.client.uuid'] = $relatedEntityUuid;
        } else {
            throw new \Exception('Unrecognized related entity type.');
        }

        return $this->machineRepository->complexFind(new PaginationProperties(), [], $filters);
    }
}
