<?php

declare(strict_types=1);

namespace App\Application\UseCase\Client\DeleteClient;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Dto\Project\ProjectDto;
use App\Application\Shared\Query\QueryBusInterface;
use App\Application\UseCase\Project\DeleteProject\DeleteProjectCommand;
use App\Application\UseCase\Project\GetProjects\GetProjectsQuery;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Shared\Entities\PaginationProperties;
use App\Domain\Shared\Errors\NotFoundError;
use App\Domain\User\Enums\PermissionType;

class DeleteClientHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function __invoke(DeleteClientCommand $deleteClientCommand): void
    {
        $client = $this->clientRepository->getByUuid($deleteClientCommand->uuid) ??
            throw new NotFoundError('Bad Client Uuid');

        $deleteClientCommand->loggedUser->checkPermissionForClient($client, PermissionType::ADMIN);

        $this->clientRepository->deleteByUuid($deleteClientCommand->uuid);

        // TODO: Replace for an event
        /** @var ProjectDto[] $projects */
        $projects = ($this->queryBus->dispatch(new GetProjectsQuery(
            $deleteClientCommand->loggedUser,
            new PaginationProperties(),
            ['client.uuid' => $deleteClientCommand->uuid],
            [],
        )))->results;

        foreach ($projects as $project) {
            $this->commandBus->dispatch(new DeleteProjectCommand(
                $deleteClientCommand->loggedUser,
                $project->uuid,
            ));
        }
    }
}
