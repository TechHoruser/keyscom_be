<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\Client;

use App\Application\Shared\Dto\Client\Output\ClientDto;
use App\Application\Shared\Mapper\MapperInterface;
use App\Domain\Client\Entity\Client;

class ClientMapper implements MapperInterface
{
    /**
     * @param Client $client
     * @return ClientDto
     */
    public function map($client): ClientDto
    {
        return new ClientDto(
            $client->getUuid(),
            $client->getName(),
        );
    }
}
