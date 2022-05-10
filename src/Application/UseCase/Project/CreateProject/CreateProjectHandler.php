<?php

declare(strict_types=1);

namespace App\Application\UseCase\Project\CreateProject;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Dto\Project\ProjectDto;
use App\Application\Shared\Mapper\Project\ProjectMapper;
use App\Domain\Project\Entity\Project;
use App\Domain\Project\Repository\ProjectRepositoryInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\User\Enums\PermissionType;

class CreateProjectHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly ClientRepositoryInterface $clientRepository,
        private readonly ProjectMapper $projectMapper,
    ) {}

    public function __invoke(CreateProjectCommand $createProjectCommand): ProjectDto
    {
        $client = $this->clientRepository->getByUuid($createProjectCommand->clientUuid) ??
            throw new \Exception('Bad Client Uuid');

        $createProjectCommand->loggedUser->checkPermissionForClient($client, PermissionType::ADMIN);

        $project = $this->projectRepository->save(new Project(
            $createProjectCommand->uuid,
            $createProjectCommand->name,
            $createProjectCommand->startDate,
            $createProjectCommand->endDate,
            $client,
        ));

        return $this->projectMapper->map($project);
    }
}
