<?php

declare(strict_types=1);

namespace App\Application\UseCase\Machine\GetMachine;

use App\Application\Shared\Query\QueryInterface;

class GetMachineQuery implements QueryInterface
{
    public function __construct(
        readonly string $uuid,
        readonly array $embeds,
    ) {}
}
