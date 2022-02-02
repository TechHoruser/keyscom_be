<?php

declare(strict_types=1);

namespace App\Application\UseCase\Client\GetClients;

use App\Application\Shared\Mapper\Client\ClientMapper;
use App\Application\Shared\Mapper\Pagination\PaginationMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\Repository\ClientRepositoryInterface;

class GetClientsHandler implements QueryHandlerInterface
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private PaginationMapper $paginationMapper,
        private ClientMapper $clientMapper,
    ) {}

    public function __invoke(GetClientsQuery $getClientsQuery)
    {
        /** @var Client[] $results */
        $results = $this->clientRepository->complexFind(
            $getClientsQuery->paginationProperties,
            $getClientsQuery->filters,
        );

        return $this->paginationMapper->map(
            $this->clientMapper->map($results),
            $this->clientRepository->countAll($getClientsQuery->filters)
        );
    }
}
