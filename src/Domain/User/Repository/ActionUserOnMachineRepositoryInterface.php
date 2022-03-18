<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Shared\Entities\PaginationProperties;
use App\Domain\Shared\Interfaces\ComplexFindInterface;
use App\Domain\User\Entity\ActionUserOnMachine;

interface ActionUserOnMachineRepositoryInterface extends ComplexFindInterface
{
    public function getByUuid(string $uuid): ?ActionUserOnMachine;

    /**
     * @param PaginationProperties $paginationProperties
     * @param string[] $embeds
     * @param string[] $filtersWithAnds
     * @param string[] $filtersWithOrs
     *
     * @return ActionUserOnMachine[]
     */
    public function complexFind(
        PaginationProperties $paginationProperties = new PaginationProperties(),
        array $embeds = [],
        array $filtersWithAnds = [],
        array $filtersWithOrs = [],
    ): iterable;

    public function save(ActionUserOnMachine $actionUserOnMachine): ActionUserOnMachine;
}
