<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\GetMachine;

use App\Application\Shared\Mapper\Machine\MachineMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Machine\Repository\MachineRepositoryInterface;

class GetMachineHandler implements QueryHandlerInterface
{
    public function __construct(
        private MachineRepositoryInterface $machineRepository,
        private MachineMapper $machineMapper,
    ) {}

    public function __invoke(GetMachineQuery $getMachineQuery)
    {
        $machine = $this->machineRepository->getByUuid(
            $getMachineQuery->uuid,
            $getMachineQuery->embeds,
        );

        return $this->machineMapper->map($machine, $getMachineQuery->embeds);
    }
}
