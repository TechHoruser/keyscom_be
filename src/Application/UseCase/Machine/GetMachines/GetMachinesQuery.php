<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\GetMachines;

use App\Application\Shared\Query\QueryInterface;
use App\Domain\Shared\Entities\PaginationProperties;

class GetMachinesQuery implements QueryInterface
{
    public function __construct(
        readonly PaginationProperties $paginationProperties,
        readonly array $filters,
        readonly array $embeds,
    ) {}
}
