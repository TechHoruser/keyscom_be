<?php

declare(strict_types=1);

namespace App\Application\UseCase\Dashboard\DashboardCurrentYear;

use App\Application\Shared\Enum\EntityType;
use App\Application\Shared\Query\QueryInterface;

class DashboardCurrentYearQuery implements QueryInterface
{
    public function __construct(
        readonly EntityType $type,
    ) {}
}
