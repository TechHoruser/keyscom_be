<?php

declare(strict_types=1);

namespace App\Application\UseCase\Client\UpdateClient;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\User\Enums\PermissionType;

class UpdateClientHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
    ) {}

    public function __invoke(UpdateClientCommand $updateClientCommand): void
    {
        $client = $this->clientRepository->getByUuid($updateClientCommand->uuid) ??
            throw new \Exception('Bad Client Uuid');

        $updateClientCommand->loggedUser->checkPermissionForClient($client, PermissionType::ADMIN);

        $client->setName($updateClientCommand->name);

        $this->clientRepository->save($client);
    }
}
