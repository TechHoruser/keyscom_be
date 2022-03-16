<?php

declare(strict_types=1);

namespace App\Application\UseCase\Project\GetProject;

use App\Application\Shared\Dto\Project\ProjectDto;
use App\Application\Shared\Mapper\Project\ProjectMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Project\Repository\ProjectRepositoryInterface;

class GetProjectHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private ProjectMapper $projectMapper,
    ) {}

    public function __invoke(GetProjectQuery $getProjectQuery): ProjectDto
    {
        $project = $this->projectRepository->getByUuid(
            $getProjectQuery->uuid,
            $getProjectQuery->embeds,
        );

        return $this->projectMapper->map($project, $getProjectQuery->embeds);
    }
}
