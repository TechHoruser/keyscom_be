<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\UpdateMySelf;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

class UpdateMySelfHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(UpdateMySelfCommand $updateMySelfCommand): void
    {
        $user = $this->userRepository->getByUuid($updateMySelfCommand->loggedUser->getUuid())
//        $user = $updateMySelfCommand->loggedUser
            ->setFirstName($updateMySelfCommand->firstName)
            ->setLastName($updateMySelfCommand->lastName)
            ->setPubKey($updateMySelfCommand->pubKey);

        $this->userRepository->save($user);
    }
}
