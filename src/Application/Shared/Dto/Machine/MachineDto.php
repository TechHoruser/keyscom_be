<?php

declare(strict_types=1);

namespace App\Application\Shared\Dto\Machine;

class MachineDto
{
    public function __construct(
        readonly ?string $uuid,
        readonly ?string $name,
        readonly string $ip,
        readonly ?string $domain,
        readonly ?string $type,
    ) {}
}
