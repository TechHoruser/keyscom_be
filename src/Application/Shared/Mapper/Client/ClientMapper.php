<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\Client;

use App\Application\Shared\Dto\Client\ClientDto;
use App\Application\Shared\Mapper\AbstractMapper;
use App\Application\Shared\Mapper\Project\ProjectMapperFromClient;
use App\Domain\Client\Entity\Client;

class ClientMapper extends AbstractMapper
{
    public function __construct(
      private readonly ProjectMapperFromClient $projectMapper,
    ) {}

    public function map(Client $client, array $embeds = []): ClientDto
    {
        $embeds = $this->getOrganizedEmbeds($embeds);

        return new ClientDto(
            $client->getUuid(),
            $client->getName(),
            !isset($embeds['projects']) ? null :
                $this->projectMapper->mapArray($client->getProjects(), $embeds['projects']),
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
