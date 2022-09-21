<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\CreateMachine;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Dto\Machine\MachineDto;
use App\Application\Shared\Mapper\Machine\MachineMapper;
use App\Domain\Machine\Entity\Machine;
use App\Domain\Machine\Repository\MachineRepositoryInterface;
use App\Domain\Project\Repository\ProjectRepositoryInterface;
use App\Domain\Shared\Errors\DomainError;
use App\Domain\User\Entity\ActionUserOnMachine;
use App\Domain\User\Enums\ActionOfUserOnMachine;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use App\Domain\User\Repository\ActionUserOnMachineRepositoryInterface;
use App\Domain\User\Repository\PermissionRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

class CreateMachineHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PermissionRepositoryInterface $permissionRepository,
        private readonly ActionUserOnMachineRepositoryInterface $actionUserOnMachineRepository,
        private readonly MachineRepositoryInterface $machineRepository,
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly MachineMapper $machineMapper,
    ) {}

    public function __invoke(CreateMachineCommand $createMachineCommand): MachineDto
    {
        $project = $this->projectRepository->getByUuid($createMachineCommand->projectUuid) ??
            throw new DomainError('Bad Project Uuid');

        $createMachineCommand->loggedUser->checkPermissionForProject($project, PermissionType::ADMIN);

        $machine = $this->machineRepository->save(new Machine(
            $createMachineCommand->uuid,
            $createMachineCommand->ip,
            $createMachineCommand->name,
            $createMachineCommand->domain,
            $createMachineCommand->type,
            $project,
        ));

        $users = $this->userRepository->complexFind();

        foreach ($users as $user) {
            $permissions = $this->permissionRepository->getParentOrSamePermissionOfUser(
                $user,
                PermissionType::SSH,
                PermissionRelatedEntity::MACHINE,
                null,
                $machine->getUuid(),
            );
            foreach ($permissions as $permission) {
                if ($user->getPubKey()) {
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

        return $this->machineMapper->map($machine);
    }
}
