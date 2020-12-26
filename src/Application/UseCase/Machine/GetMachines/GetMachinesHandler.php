<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\GetMachines;

use App\Application\Shared\Mapper\Pagination\PaginationMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Machine\Repository\MachineRepositoryInterface;

class GetMachinesHandler implements QueryHandlerInterface
{
    private MachineRepositoryInterface $machineRepository;
    private PaginationMapper $paginationMapper;

    public function __construct(
        MachineRepositoryInterface $machineRepository,
        PaginationMapper $paginationMapper
    ) {
        $this->machineRepository = $machineRepository;
        $this->paginationMapper = $paginationMapper;
    }

    public function __invoke(GetMachinesQuery $getMachinesQuery)
    {
        /** @var array $results */
        $results = $this->machineRepository->complexFind(
            $getMachinesQuery->getPage(),
            $getMachinesQuery->getResultsPerPage(),
            $getMachinesQuery->getSortBy(),
            $getMachinesQuery->getSortOrder(),
            $getMachinesQuery->getFilters()
        );

        return $this->paginationMapper->map(
            $this->getMachinesOutputMapper->map($results),
            $this->machineRepository->countAll($getMachinesQuery->getFilters())
        );
    }
}
