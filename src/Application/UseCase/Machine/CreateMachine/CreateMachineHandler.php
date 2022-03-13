<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\CreateMachine;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Dto\Machine\MachineDto;
use App\Application\Shared\Mapper\Machine\MachineMapper;
use App\Domain\Machine\Entity\Machine;
use App\Domain\Machine\Repository\MachineRepositoryInterface;
use App\Domain\Project\Repository\ProjectRepositoryInterface;

class CreateMachineHandler implements CommandHandlerInterface
{
    public function __construct(
        private MachineRepositoryInterface $machineRepository,
        private ProjectRepositoryInterface $projectRepository,
        private MachineMapper $machineMapper,
    ) {}

    public function __invoke(CreateMachineCommand $createMachineCommand): MachineDto
    {
        $project = $this->projectRepository->getByUuid($createMachineCommand->projectUuid);

        if (!$project) {
            throw new \Exception('Bad Project Uuid');
        }

        $machine = $this->machineRepository->save(new Machine(
            $createMachineCommand->uuid,
            $createMachineCommand->ip,
            $createMachineCommand->name,
            $createMachineCommand->domain,
            $createMachineCommand->type,
            $project,
        ));

        return $this->machineMapper->map($machine);
    }
}
