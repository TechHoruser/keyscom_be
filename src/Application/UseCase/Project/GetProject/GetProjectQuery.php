<?php

declare(strict_types=1);

namespace App\Application\UseCase\Project\GetProject;

use App\Application\Shared\Query\QueryInterface;

class GetProjectQuery implements QueryInterface
{
    public function __construct(
        readonly string $uuid,
        readonly array $embeds,
    ) {}
}
