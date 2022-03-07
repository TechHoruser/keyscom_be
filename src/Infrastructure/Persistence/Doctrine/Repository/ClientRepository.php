<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Client\Entity\Client;
use App\Domain\Client\Repository\ClientRepositoryInterface;

class ClientRepository extends AbstractRepository implements ClientRepositoryInterface
{
    protected function getAliasTable(): string
    {
        return 'clients';
    }

    protected function getEntityRepositoryClass(): string
    {
        return Client::class;
    }

    public function getByUuid(string $uuid, array $embeds = []): ?Client
    {
        return parent::getByUuid($uuid, $embeds);
    }

    public function save(Client $client): Client
    {
        return parent::saveEntityInterface($client);
    }
}
