<?php

declare(strict_types=1);

namespace App\Domain\Client\Repository;

use App\Domain\Client\Entity\Client;
use App\Domain\Shared\Entities\PaginationProperties;
use App\Domain\Shared\Interfaces\ComplexFindInterface;

interface ClientRepositoryInterface extends ComplexFindInterface
{
    /**
     * @param PaginationProperties $paginationProperties
     * @param string[] $filters
     * @param string[] $embeds
     *
     * @return Client[]
     */
    public function complexFind(
        PaginationProperties $paginationProperties = new PaginationProperties(),
        array $filters = [],
        array $embeds = [],
    ): iterable;

    public function getByUuid(string $uuid): ?Client;
    public function deleteByUuid(string $uuid): void;
    public function save(Client $client): Client;
}
