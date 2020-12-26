<?php

declare(strict_types=1);

namespace App\Application\UseCase\Project\GetProjects;

use App\Application\Shared\Mapper\Pagination\PaginationMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Project\Repository\ProjectRepositoryInterface;

class GetProjectsHandler implements QueryHandlerInterface
{
    private ProjectRepositoryInterface $machineRepository;
    private PaginationMapper $paginationMapper;

    public function __construct(
        ProjectRepositoryInterface $machineRepository,
        PaginationMapper $paginationMapper
    ) {
        $this->machineRepository = $machineRepository;
        $this->paginationMapper = $paginationMapper;
    }

    public function __invoke(GetProjectsQuery $getProjectsQuery)
    {
        /** @var array $results */
        $results = $this->machineRepository->complexFind(
            $getProjectsQuery->getPage(),
            $getProjectsQuery->getResultsPerPage(),
            $getProjectsQuery->getSortBy(),
            $getProjectsQuery->getSortOrder(),
            $getProjectsQuery->getFilters()
        );

        return $this->paginationMapper->map(
            $this->getProjectsOutputMapper->map($results),
            $this->machineRepository->countAll($getProjectsQuery->getFilters())
        );
    }
}
