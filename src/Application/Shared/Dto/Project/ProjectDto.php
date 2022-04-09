<?php

declare(strict_types=1);

namespace App\Application\Shared\Dto\Project;

use App\Application\Shared\Dto\Client\ClientDto;

class ProjectDto
{
    public function __construct(
        readonly ?string $uuid,
        readonly string $name,
        readonly ?\DateTime $startDate,
        readonly ?\DateTime $endDate,
        readonly ?ClientDto $client,
        readonly ?array $machines,
    ) {}
}
