<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\GetMachine;

use App\Application\Shared\Dto\Machine\MachineDto;
use App\Application\Shared\Mapper\Machine\MachineMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Machine\Repository\MachineRepositoryInterface;
use App\Domain\Shared\Errors\NotFoundError;

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
        ) ?? throw new NotFoundError('Bad Machine Uuid');

        $getMachineQuery->loggedUser->checkPermissionForMachine($machine);

        return $this->machineMapper->map($machine, $getMachineQuery->embeds);
    }
}
