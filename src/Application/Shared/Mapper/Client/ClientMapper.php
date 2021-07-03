<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\Client;

use App\Application\Shared\Dto\Client\ClientDto;
use App\Application\Shared\Mapper\MapperInterface;
use App\Domain\Client\Entity\Client;

class ClientMapper implements MapperInterface
{
    /**
     * @param Client[] $clients
     * @return ClientDto[]
     */
    public function map($clients): iterable
    {
        $clientsDto = [];

        foreach ($clients as $client) {
            $clientsDto[] = new ClientDto(
                $client->getUuid(),
                $client->getName()
            );
        }

        return $clientsDto;
    }
}
