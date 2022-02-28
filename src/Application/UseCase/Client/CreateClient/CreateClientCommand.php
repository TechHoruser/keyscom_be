<?php

declare(strict_types=1);

namespace App\Application\UseCase\Client\CreateClient;

use App\Application\Shared\Command\CommandInterface;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;

class CreateClientCommand implements CommandInterface
{
    public function __construct(
        readonly ?string $uuid,
        readonly string $name,
    ) {}
}
