<?php

declare(strict_types=1);

namespace App\Application\Shared\Dto\Machine;

use App\Application\Shared\Dto\Project\ProjectDto;

class MachineDto
{
    public function __construct(
        readonly ?string $uuid,
        readonly ?string $name,
        readonly string $ip,
        readonly ?string $domain,
        readonly ?string $type,
        readonly ?ProjectDto $project,
    ) {}
}
