<?php

declare(strict_types=1);

namespace App\Domain\Shared\Interfaces;

use App\Domain\Shared\Entities\PaginationProperties;

interface ComplexFindInterface
{
    public function complexFind(
        PaginationProperties $paginationProperties = new PaginationProperties(),
        array $filters = [],
    ): iterable;

    public function countAll($filters = []): int;
}
