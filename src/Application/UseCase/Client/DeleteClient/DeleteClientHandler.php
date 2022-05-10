<?php

declare(strict_types=1);

namespace App\Application\UseCase\Client\DeleteClient;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\User\Enums\PermissionType;

class DeleteClientHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
    ) {}

    public function __invoke(DeleteClientCommand $deleteClientCommand): void
    {
        $client = $this->clientRepository->getByUuid($deleteClientCommand->uuid) ??
            throw new \Exception('Bad Client Uuid');

        $deleteClientCommand->loggedUser->checkPermissionForClient($client, PermissionType::ADMIN);

        $this->clientRepository->deleteByUuid($deleteClientCommand->uuid);
    }
}
