<?php

declare(strict_types=1);

namespace App\Domain\Machine\Repository;

use App\Domain\Machine\Entity\Machine;
use App\Domain\Shared\Entities\PaginationProperties;
use App\Domain\Shared\Interfaces\ComplexFindInterface;

interface MachineRepositoryInterface extends ComplexFindInterface
{
    /**
     * @param PaginationProperties $paginationProperties
     * @param string[] $embeds
     * @param string[] $filtersWithAnds
     * @param string[] $filtersWithOrs
     *
     * @return Machine[]
     */
    public function complexFind(
        PaginationProperties $paginationProperties = new PaginationProperties(),
        array $embeds = [],
        array $filtersWithAnds = [],
        array $filtersWithOrs = [],
    ): iterable;

    public function getByUuid(string $uuid): ?Machine;
    public function deleteByUuid(string $uuid): void;
    public function save(Machine $machine): Machine;
}

