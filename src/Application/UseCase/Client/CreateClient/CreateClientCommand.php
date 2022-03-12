<?php

declare(strict_types=1);

namespace App\Application\UseCase\Client\CreateClient;

use App\Application\Shared\Command\CommandInterface;

class CreateClientCommand implements CommandInterface
{
    public function __construct(
        readonly ?string $uuid,
        readonly string $name,
    ) {}
}
