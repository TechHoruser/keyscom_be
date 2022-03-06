<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\Client;

use App\Application\Shared\Dto\Client\ClientDto;
use App\Application\Shared\Mapper\AbstractMapper;
use App\Domain\Client\Entity\Client;

class ClientMapperFromProject extends AbstractMapper
{
    public function map(Client $client, array $embeds = []): ClientDto
    {
        return new ClientDto(
            $client->getUuid(),
            $client->getName(),
            null,
        );
    }

    /**
     * @param Client[] $clients
     * @param string[] $embeds
     *
     * @return ClientDto[]
     */
    public function mapArray(array $clients, array $embeds = []): array
    {
        return parent::mapArray($clients, $embeds);
    }
}
