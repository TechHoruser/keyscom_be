<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\UpdateUser;

use App\Application\Shared\Command\CommandInterface;
use App\Domain\User\Entity\User;

class UpdateUserCommand implements CommandInterface
{
    public function __construct(
        readonly User $loggedUser,
        readonly string $uuid,
        readonly string $email,
        readonly string $firstName,
        readonly string $lastName,
    ) {}
}
