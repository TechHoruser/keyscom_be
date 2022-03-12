<?php

declare(strict_types=1);

namespace App\Application\UseCase\Client\DeleteClient;

use App\Application\Shared\Command\CommandInterface;

class DeleteClientCommand implements CommandInterface
{
    public function __construct(
        readonly string $uuid,
    ) {}
}
