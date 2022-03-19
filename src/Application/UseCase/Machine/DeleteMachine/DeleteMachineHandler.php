<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\DeleteMachine;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Machine\Repository\MachineRepositoryInterface;
use App\Domain\User\Enums\PermissionType;

class DeleteMachineHandler implements CommandHandlerInterface
{
    public function __construct(
        private MachineRepositoryInterface $machineRepository,
    ) {}

    public function __invoke(DeleteMachineCommand $deleteMachineCommand): void
    {
        $machine = $this->machineRepository->getByUuid($deleteMachineCommand->uuid) ??
            throw new \Exception('Bad Machine Uuid');

        $deleteMachineCommand->loggedUser->checkPermissionForMachine($machine, PermissionType::ADMIN);

        $this->machineRepository->deleteByUuid($deleteMachineCommand->uuid);
    }
}
