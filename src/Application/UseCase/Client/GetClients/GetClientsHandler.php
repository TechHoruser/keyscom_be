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
    private ClientRepositoryInterface $clientRepository;
    private PaginationMapper $paginationMapper;
    private ClientMapper $clientMapper;

    /**
     * GetClientsHandler constructor.
     * @param ClientRepositoryInterface $clientRepository
     * @param PaginationMapper $paginationMapper
     * @param ClientMapper $clientMapper
     */
    public function __construct(
        ClientRepositoryInterface $clientRepository,
        PaginationMapper $paginationMapper,
        ClientMapper $clientMapper
    ) {
        $this->clientRepository = $clientRepository;
        $this->paginationMapper = $paginationMapper;
        $this->clientMapper = $clientMapper;
    }

    public function __invoke(GetClientsQuery $getClientsQuery)
    {
        /** @var Client[] $results */
        $results = $this->clientRepository->complexFind(
            $getClientsQuery->getPage(),
            $getClientsQuery->getResultsPerPage(),
            $getClientsQuery->getSortBy(),
            $getClientsQuery->getSortOrder(),
            $getClientsQuery->getFilters()
        );

        return $this->paginationMapper->map(
            $this->clientMapper->map($results),
            $this->clientRepository->countAll($getClientsQuery->getFilters())
        );
    }
}
