<?php

declare(strict_types=1);

namespace App\Application\Shared\Dto\Dashboard;

class DashboardCardsDto
{
    public function __construct(
        readonly int $users,
        readonly int $clients,
        readonly int $projects,
        readonly int $machines,
    ) {}
}
