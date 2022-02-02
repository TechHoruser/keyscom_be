<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Shared\Entities\PaginationProperties;
use App\Domain\User\Entity\ActionUserOnMachine;

interface ActionUserOnMachineRepositoryInterface
{
    public function getByUuid(string $uuid): ?ActionUserOnMachine;

    public function complexFind(
        PaginationProperties $paginationProperties,
        array $filters = [],
    ): iterable;

    public function countAll($filters = []): int;

    public function save(ActionUserOnMachine $actionUserOnMachine): ActionUserOnMachine;
}
