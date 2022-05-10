<?php

declare(strict_types=1);

namespace App\Application\UseCase\Dashboard\DashboardCurrentYear;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Application\Shared\Repository\AnalyticsRepositoryInterface;

class DashboardCurrentYearHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly AnalyticsRepositoryInterface $analyticsRepository,
    ) {}

    public function __invoke(DashboardCurrentYearQuery $dashboardCurrentYearQuery): array
    {
        return $this->analyticsRepository->getCountOfNewEntitiesByDay($dashboardCurrentYearQuery->type);
    }
}
