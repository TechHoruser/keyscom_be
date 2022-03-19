<?php

declare(strict_types=1);

namespace App\Application\UseCase\Client\DeleteClient;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\User\Enums\PermissionType;

class DeleteClientHandler implements CommandHandlerInterface
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {}

    public function __invoke(DeleteClientCommand $createClientCommand): void
    {
        $client = $this->clientRepository->getByUuid($createClientCommand->uuid) ??
            throw new \Exception('Bad Client Uuid');

        $createClientCommand->loggedUser->checkPermissionForClient($client, PermissionType::ADMIN);

        $this->clientRepository->deleteByUuid($createClientCommand->uuid);
    }
}
