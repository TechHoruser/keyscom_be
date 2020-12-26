<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Command\CommandInterface;

abstract class AbstractCommandController
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    protected function dispatch(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
