<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\GetMachine;

use App\Application\Shared\Dto\Machine\MachineDto;
use App\Application\Shared\Mapper\Machine\MachineMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Machine\Repository\MachineRepositoryInterface;

class GetMachineHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly MachineRepositoryInterface $machineRepository,
        private readonly MachineMapper $machineMapper,
    ) {}

    public function __invoke(GetMachineQuery $getMachineQuery): MachineDto
    {
        $machine = $this->machineRepository->getByUuid(
            $getMachineQuery->uuid,
            $getMachineQuery->embeds,
        ) ?? throw new \Exception('Bad Machine Uuid');

        $getMachineQuery->loggedUser->checkPermissionForMachine($machine);

        return $this->machineMapper->map($machine, $getMachineQuery->embeds);
    }
}
