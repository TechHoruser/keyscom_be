<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Command\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerCommandBus implements CommandBusInterface
{
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function dispatch(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
