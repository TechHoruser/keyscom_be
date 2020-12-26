<?php

declare(strict_types=1);

namespace App\Application\UseCase\Client\GetClients;

use App\Application\Shared\Mapper\Pagination\PaginationMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;

class GetClientsHandler implements QueryHandlerInterface
{
    private ClientRepositoryInterface $machineRepository;
    private PaginationMapper $paginationMapper;

    public function __construct(
        ClientRepositoryInterface $machineRepository,
        PaginationMapper $paginationMapper
    ) {
        $this->machineRepository = $machineRepository;
        $this->paginationMapper = $paginationMapper;
    }

    public function __invoke(GetClientsQuery $getClientsQuery)
    {
        /** @var array $results */
        $results = $this->machineRepository->complexFind(
            $getClientsQuery->getPage(),
            $getClientsQuery->getResultsPerPage(),
            $getClientsQuery->getSortBy(),
            $getClientsQuery->getSortOrder(),
            $getClientsQuery->getFilters()
        );

        return $this->paginationMapper->map(
            $this->getClientsOutputMapper->map($results),
            $this->machineRepository->countAll($getClientsQuery->getFilters())
        );
    }
}
