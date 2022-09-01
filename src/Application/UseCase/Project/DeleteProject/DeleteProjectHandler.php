<?php

declare(strict_types=1);

namespace App\Application\UseCase\Project\DeleteProject;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Project\Repository\ProjectRepositoryInterface;
use App\Domain\Shared\Errors\NotFoundError;
use App\Domain\User\Enums\PermissionType;

class DeleteProjectHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
    ) {}

    public function __invoke(DeleteProjectCommand $deleteProjectCommand): void
    {
        $project = $this->projectRepository->getByUuid($deleteProjectCommand->uuid) ??
            throw new NotFoundError('Bad Project Uuid');

        $deleteProjectCommand->loggedUser->checkPermissionForProject($project, PermissionType::ADMIN);

        $this->projectRepository->deleteByUuid($deleteProjectCommand->uuid);
    }
}
