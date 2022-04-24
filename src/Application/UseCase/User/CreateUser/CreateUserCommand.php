<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\CreateUser;

use App\Application\Shared\Command\CommandInterface;
use App\Domain\User\Entity\User;

class CreateUserCommand implements CommandInterface
{
    public function __construct(
        readonly User $loggedUser,
        readonly ?string $uuid,
        readonly string $email,
        readonly string $firstName,
        readonly string $lastName,
    ) {}
}
