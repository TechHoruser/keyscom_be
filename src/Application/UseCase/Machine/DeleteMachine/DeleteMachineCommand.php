<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\DeleteMachine;

use App\Application\Shared\Command\CommandInterface;
use App\Domain\User\Entity\User;

class DeleteMachineCommand implements CommandInterface
{
    public function __construct(
        readonly User $loggedUser,
        readonly string $uuid,
    ) {}
}
