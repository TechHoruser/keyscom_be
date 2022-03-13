<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\DeleteMachine;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Machine\Repository\MachineRepositoryInterface;

class DeleteMachineHandler implements CommandHandlerInterface
{
    public function __construct(
        private MachineRepositoryInterface $machineRepository,
    ) {}

    public function __invoke(DeleteMachineCommand $createMachineCommand): void
    {
        $this->machineRepository->deleteByUuid($createMachineCommand->uuid);
    }
}
