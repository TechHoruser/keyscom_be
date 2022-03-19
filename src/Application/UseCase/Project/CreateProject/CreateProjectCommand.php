<?php

declare(strict_types=1);

namespace App\Application\UseCase\Project\CreateProject;

use App\Application\Shared\Command\CommandInterface;
use App\Domain\User\Entity\User;

class CreateProjectCommand implements CommandInterface
{
    public function __construct(
        readonly User $loggedUser,
        readonly ?string $uuid,
        readonly string $name,
        readonly ?\DateTime $startDate,
        readonly ?\DateTime $endDate,
        readonly string $clientUuid,
    ) {}
}
