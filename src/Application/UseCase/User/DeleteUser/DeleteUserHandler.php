<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\DeleteUser;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Enums\PermissionType;

class DeleteUserHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(DeleteUserCommand $deleteUserCommand): void
    {
        $deleteUserCommand->loggedUser->checkSuperPermission(PermissionType::ADMIN);

        $this->userRepository->deleteByUuid($deleteUserCommand->uuid);
    }
}
