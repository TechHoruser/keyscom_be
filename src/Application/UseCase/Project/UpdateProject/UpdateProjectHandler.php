<?php

declare(strict_types=1);

namespace App\Application\UseCase\Project\UpdateProject;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Project\Repository\ProjectRepositoryInterface;
use App\Domain\User\Enums\PermissionType;

class UpdateProjectHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
    ) {}

    public function __invoke(UpdateProjectCommand $updateProjectCommand): void
    {
        $project = $this->projectRepository->getByUuid($updateProjectCommand->uuid) ??
            throw new \Exception('Bad Project Uuid');

        $updateProjectCommand->loggedUser->checkPermissionForProject($project, PermissionType::ADMIN);

        $project->setName($updateProjectCommand->name);

        $this->projectRepository->save($project);
    }
}
