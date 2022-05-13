<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\UpdateUser;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\User\Enums\PermissionType;
use App\Domain\User\Repository\UserRepositoryInterface;

class UpdateUserHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(UpdateUserCommand $updateUserCommand): void
    {
        $updateUserCommand->loggedUser->checkSuperPermission(PermissionType::ADMIN);

        $user = $this->userRepository->getByUuid($updateUserCommand->uuid) ??
            throw new \Exception('Bad User Uuid');

        $user
            ->setEmail($updateUserCommand->email)
            ->setFirstName($updateUserCommand->firstName)
            ->setLastName($updateUserCommand->lastName);

        $this->userRepository->save($user);
    }
}
