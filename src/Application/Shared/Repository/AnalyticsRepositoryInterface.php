<?php

declare(strict_types=1);

namespace App\Application\Shared\Repository;

use App\Application\Shared\Enum\EntityType;

interface AnalyticsRepositoryInterface
{
    public function getCountOfNewEntitiesByDay(EntityType $type): array;
}
