<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\DeleteUser;

use App\Application\Shared\Command\CommandInterface;
use App\Domain\User\Entity\User;

class DeleteUserCommand implements CommandInterface
{
    public function __construct(
        readonly User $loggedUser,
        readonly string $uuid,
    ) {}
}
