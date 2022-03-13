<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\DeleteMachine;

use App\Application\Shared\Command\CommandInterface;

class DeleteMachineCommand implements CommandInterface
{
    public function __construct(
        readonly string $uuid,
    ) {}
}
