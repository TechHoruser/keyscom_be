<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\DeleteUser;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\UseCase\User\RevokePermissionsByEntity\RevokePermissionsByEntityCommand;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Enums\PermissionType;

class DeleteUserHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function __invoke(DeleteUserCommand $deleteUserCommand): void
    {
        $deleteUserCommand->loggedUser->checkSuperPermission(PermissionType::ADMIN);

        $this->commandBus->dispatch(new RevokePermissionsByEntityCommand(
            $deleteUserCommand->loggedUser,
            $deleteUserCommand->userUuid,
            null,
            null,
            null,
        ));

        $this->userRepository->deleteByUuid($deleteUserCommand->userUuid);
    }
}
