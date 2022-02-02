<?php

declare(strict_types=1);

namespace App\Application\Shared\Dto\Project;

class ProjectDto
{
    public function __construct(
        readonly ?string $uuid,
        readonly string $name,
    ) {}
}
