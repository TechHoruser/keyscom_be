<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

interface UserRepositoryInterface
{
    public function complexFind(
        int $page = 0,
        int $pageSize = 0,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        array $filters = []
    ): iterable;

    public function countAll($filters = []): int;
}
