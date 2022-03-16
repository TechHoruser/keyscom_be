<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\GetMachines;

use App\Application\Shared\Dto\Pagination\PaginationDto;
use App\Application\Shared\Mapper\Machine\MachineMapper;
use App\Application\Shared\Mapper\Pagination\PaginationMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Machine\Repository\MachineRepositoryInterface;

class GetMachinesHandler implements QueryHandlerInterface
{
    public function __construct(
        private MachineRepositoryInterface $machineRepository,
        private PaginationMapper $paginationMapper,
        private MachineMapper $machineMapper,
    ) {}

    public function __invoke(GetMachinesQuery $getMachinesQuery): PaginationDto
    {
        $results = $this->machineRepository->complexFind(
            $getMachinesQuery->paginationProperties,
            $getMachinesQuery->filters,
            $getMachinesQuery->embeds,
        );

        return $this->paginationMapper->map(
            $this->machineMapper->mapArray($results, $getMachinesQuery->embeds),
            $this->machineRepository->countAll($getMachinesQuery->filters)
        );
    }
}
