<?php

declare(strict_types=1);

namespace App\Domain\Shared\Interfaces;

use App\Domain\Shared\Entities\PaginationProperties;

interface ComplexFindInterface
{
    /**
     * @param PaginationProperties $paginationProperties
     * @param string[] $embeds
     * @param string[] $filtersWithAnds
     * @param string[] $filtersWithOrs
     *
     * @return iterable
     */
    public function complexFind(
        PaginationProperties $paginationProperties = new PaginationProperties(),
        array $embeds = [],
        array $filtersWithAnds = [],
        array $filtersWithOrs = [],
    ): iterable;

    public function countAll(
        array $filtersWithAnds = [],
        array $filtersWithOrs = [],
    ): int;
}
