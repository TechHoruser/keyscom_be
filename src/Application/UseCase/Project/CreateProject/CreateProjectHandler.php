<?php

declare(strict_types=1);

namespace App\Application\UseCase\Project\CreateProject;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Dto\Project\ProjectDto;
use App\Application\Shared\Mapper\Project\ProjectMapper;
use App\Domain\Project\Entity\Project;
use App\Domain\Project\Repository\ProjectRepositoryInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;

class CreateProjectHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private ClientRepositoryInterface $clientRepository,
        private ProjectMapper $projectMapper,
    ) {}

    public function __invoke(CreateProjectCommand $createProjectCommand): ProjectDto
    {
        $project = $this->projectRepository->save(new Project(
            $createProjectCommand->uuid,
            $createProjectCommand->name,
            $createProjectCommand->startDate,
            $createProjectCommand->endDate,
            $this->clientRepository->getByUuid($createProjectCommand->clientUuid) ??
            throw new \Exception('Bad Client Uuid'),
        ));

        return $this->projectMapper->map($project);
    }
}
