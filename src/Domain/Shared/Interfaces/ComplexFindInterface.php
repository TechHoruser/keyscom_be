<?php

declare(strict_types=1);

namespace App\Domain\Shared\Interfaces;

use App\Domain\Shared\Entities\PaginationProperties;

interface ComplexFindInterface
{
    /**
     * @param PaginationProperties $paginationProperties
     * @param string[] $filters
     * @param string[] $embeds
     *
     * @return iterable
     */
    public function complexFind(
        PaginationProperties $paginationProperties = new PaginationProperties(),
        array $filters = [],
        array $embeds = [],
    ): iterable;

    public function countAll($filters = []): int;
}
