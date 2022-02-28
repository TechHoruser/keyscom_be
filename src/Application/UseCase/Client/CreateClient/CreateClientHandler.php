<?php

declare(strict_types=1);

namespace App\Application\UseCase\Client\CreateClient;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Dto\Client\Output\ClientDto;
use App\Application\Shared\Mapper\Client\ClientMapper;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\Repository\ClientRepositoryInterface;

class CreateClientHandler implements CommandHandlerInterface
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private ClientMapper $clientMapper,
    ) {}

    public function __invoke(CreateClientCommand $createClientCommand): ClientDto
    {
        $client = $this->clientRepository->save(new Client(
            $createClientCommand->uuid,
            $createClientCommand->name,
        ));

        return $this->clientMapper->map($client);
    }
}
