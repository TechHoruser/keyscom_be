<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Client\Entity\Client;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Shared\Entities\PaginationProperties;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;

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

    public function getByUuid(string $uuid): ?Client
    {
        return parent::getByUuid($uuid);
    }

    public function save(Client $client): Client
    {
//        TODO: Review warning
        return parent::saveEntityInterface($client);
    }
}
