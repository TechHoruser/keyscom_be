<?php

declare(strict_types=1);

namespace App\Application\UseCase\Project\DeleteProject;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Dto\Machine\MachineDto;
use App\Application\Shared\Query\QueryBusInterface;
use App\Application\UseCase\Machine\DeleteMachine\DeleteMachineCommand;
use App\Application\UseCase\Machine\GetMachines\GetMachinesQuery;
use App\Domain\Project\Repository\ProjectRepositoryInterface;
use App\Domain\Shared\Entities\PaginationProperties;
use App\Domain\Shared\Errors\NotFoundError;
use App\Domain\User\Enums\PermissionType;

class DeleteProjectHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function __invoke(DeleteProjectCommand $deleteProjectCommand): void
    {
        $project = $this->projectRepository->getByUuid($deleteProjectCommand->uuid) ??
            throw new NotFoundError('Bad Project Uuid');

        $deleteProjectCommand->loggedUser->checkPermissionForProject($project, PermissionType::ADMIN);

        $this->projectRepository->deleteByUuid($deleteProjectCommand->uuid);

        // TODO: Replace for an event
        /** @var MachineDto[] $machines */
        $machines = ($this->queryBus->dispatch(new GetMachinesQuery(
            $deleteProjectCommand->loggedUser,
            new PaginationProperties(),
            ['project.uuid' => $deleteProjectCommand->uuid],
            [],
        )))->results;

        foreach ($machines as $machine) {
            $this->commandBus->dispatch(new DeleteMachineCommand(
                $deleteProjectCommand->loggedUser,
                $machine->uuid,
            ));
        }
    }
}
