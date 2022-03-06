<?php

declare(strict_types=1);

namespace App\Application\UseCase\Client\GetClient;

use App\Application\Shared\Query\QueryInterface;

class GetClientQuery implements QueryInterface
{
    public function __construct(
        readonly string $uuid,
        readonly array $embeds,
    ) {}
}
