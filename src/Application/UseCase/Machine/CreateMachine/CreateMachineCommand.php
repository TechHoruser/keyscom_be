<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\CreateMachine;

use App\Application\Shared\Command\CommandInterface;

class CreateMachineCommand implements CommandInterface
{
    public function __construct(
        readonly ?string $uuid,
        readonly string $ip,
        readonly ?string $name,
        readonly ?string $domain,
        readonly ?string $type,
        readonly string $projectUuid,
    ) {}
}
