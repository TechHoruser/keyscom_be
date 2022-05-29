<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\UpdateMachine;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Machine\Repository\MachineRepositoryInterface;
use App\Domain\Shared\Errors\NotFoundError;
use App\Domain\User\Enums\PermissionType;

class UpdateMachineHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly MachineRepositoryInterface $machineRepository,
    ) {}

    public function __invoke(UpdateMachineCommand $updateMachineCommand): void
    {
        $machine = $this->machineRepository->getByUuid($updateMachineCommand->uuid) ??
            throw new NotFoundError('Not Found Machine');

        $updateMachineCommand->loggedUser->checkPermissionForMachine($machine, PermissionType::ADMIN);

        $machine->setName($updateMachineCommand->name);
        $machine->setDomain($updateMachineCommand->domain);

        $this->machineRepository->save($machine);
    }
}
