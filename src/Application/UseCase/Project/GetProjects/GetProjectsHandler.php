<?php

declare(strict_types=1);

namespace App\Application\UseCase\Project\GetProjects;

use App\Application\Shared\Mapper\Pagination\PaginationMapper;
use App\Application\Shared\Mapper\Project\ProjectMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Project\Repository\ProjectRepositoryInterface;

class GetProjectsHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProjectRepositoryInterface $machineRepository,
        private PaginationMapper $paginationMapper,
        private ProjectMapper $projectMapper,
    ) {}

    public function __invoke(GetProjectsQuery $getProjectsQuery)
    {
        /** @var array $results */
        $results = $this->machineRepository->complexFind(
            $getProjectsQuery->paginationProperties,
            $getProjectsQuery->filters,
        );

        return $this->paginationMapper->map(
            $this->projectMapper->map($results),
            $this->machineRepository->countAll($getProjectsQuery->filters)
        );
    }
}
