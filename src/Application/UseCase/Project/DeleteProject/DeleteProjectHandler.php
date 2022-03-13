<?php

declare(strict_types=1);

namespace App\Application\UseCase\Project\DeleteProject;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Project\Repository\ProjectRepositoryInterface;

class DeleteProjectHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProjectRepositoryInterface $machineRepository,
    ) {}

    public function __invoke(DeleteProjectCommand $createProjectCommand): void
    {
        $this->machineRepository->deleteByUuid($createProjectCommand->uuid);
    }
}
