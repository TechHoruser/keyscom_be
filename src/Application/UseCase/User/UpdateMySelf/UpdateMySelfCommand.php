<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\UpdateMySelf;

use App\Application\Shared\Command\CommandInterface;
use App\Domain\User\Entity\User;

class UpdateMySelfCommand implements CommandInterface
{
    public function __construct(
        readonly User $loggedUser,
        readonly string $firstName,
        readonly string $lastName,
        readonly string $pubKey,
    ) {}
}
