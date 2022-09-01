<?php

declare(strict_types=1);

namespace App\Application\UseCase\Project\GetProject;

use App\Application\Shared\Dto\Project\ProjectDto;
use App\Application\Shared\Mapper\Project\ProjectMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Project\Repository\ProjectRepositoryInterface;
use App\Domain\Shared\Errors\NotFoundError;

class GetProjectHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly ProjectMapper $projectMapper,
    ) {}

    public function __invoke(GetProjectQuery $getProjectQuery): ProjectDto
    {
        $project = $this->projectRepository->getByUuid(
            $getProjectQuery->uuid,
            $getProjectQuery->embeds,
        ) ?? throw new NotFoundError('Bad Project Uuid');

        $getProjectQuery->loggedUser->checkPermissionForProject($project);

        return $this->projectMapper->map($project, $getProjectQuery->embeds);
    }
}
