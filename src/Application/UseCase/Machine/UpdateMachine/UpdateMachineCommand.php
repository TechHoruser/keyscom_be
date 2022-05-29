<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\UpdateMachine;

use App\Application\Shared\Command\CommandInterface;
use App\Domain\User\Entity\User;

class UpdateMachineCommand implements CommandInterface
{
    public function __construct(
        readonly User $loggedUser,
        readonly ?string $uuid,
        readonly string $name,
        readonly ?string $domain,
    ) {}
}
