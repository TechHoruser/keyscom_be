<?php

declare(strict_types=1);

namespace App\Application\UseCase\Project\DeleteProject;

use App\Application\Shared\Command\CommandInterface;

class DeleteProjectCommand implements CommandInterface
{
    public function __construct(
        readonly string $uuid,
    ) {}
}
