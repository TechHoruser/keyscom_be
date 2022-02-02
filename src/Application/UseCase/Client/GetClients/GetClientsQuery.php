<?php

declare(strict_types=1);

namespace App\Application\UseCase\Client\GetClients;

use App\Application\Shared\Query\QueryInterface;
use App\Domain\Shared\Entities\PaginationProperties;

class GetClientsQuery implements QueryInterface
{
    public function __construct(
        readonly PaginationProperties $paginationProperties,
        readonly array $filters,
    ) {}
}
