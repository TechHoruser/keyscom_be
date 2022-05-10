<?php

declare(strict_types=1);

namespace App\Application\UseCase\Client\UpdateClient;

use App\Application\Shared\Command\CommandInterface;
use App\Domain\User\Entity\User;

class UpdateClientCommand implements CommandInterface
{
    public function __construct(
        readonly User $loggedUser,
        readonly ?string $uuid,
        readonly string $name,
    ) {}
}
