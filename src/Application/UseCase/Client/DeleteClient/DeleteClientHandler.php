<?php

declare(strict_types=1);

namespace App\Application\UseCase\Client\DeleteClient;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;

class DeleteClientHandler implements CommandHandlerInterface
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {}

    public function __invoke(DeleteClientCommand $createClientCommand): void
    {
        $this->clientRepository->deleteByUuid($createClientCommand->uuid);
    }
}
