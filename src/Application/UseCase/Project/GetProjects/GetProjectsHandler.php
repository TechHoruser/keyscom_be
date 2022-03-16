<?php

declare(strict_types=1);

namespace App\Application\UseCase\Project\GetProjects;

use App\Application\Shared\Dto\Pagination\PaginationDto;
use App\Application\Shared\Mapper\Pagination\PaginationMapper;
use App\Application\Shared\Mapper\Project\ProjectMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Project\Repository\ProjectRepositoryInterface;

class GetProjectsHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private PaginationMapper $paginationMapper,
        private ProjectMapper $projectMapper,
    ) {}

    public function __invoke(GetProjectsQuery $getProjectsQuery): PaginationDto
    {
        $results = $this->projectRepository->complexFind(
            $getProjectsQuery->paginationProperties,
            $getProjectsQuery->filters,
            $getProjectsQuery->embeds,
        );

        return $this->paginationMapper->map(
            $this->projectMapper->mapArray($results, $getProjectsQuery->embeds),
            $this->projectRepository->countAll($getProjectsQuery->filters)
        );
    }
}
