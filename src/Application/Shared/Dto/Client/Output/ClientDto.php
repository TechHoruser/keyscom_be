<?php

declare(strict_types=1);

namespace App\Application\Shared\Dto\Client\Output;

class ClientDto
{
    public function __construct(
        readonly ?string $uuid,
        readonly string $name,
    ) {}
}
