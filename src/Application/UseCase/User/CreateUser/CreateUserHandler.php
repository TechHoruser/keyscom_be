<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\CreateUser;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Dto\User\UserDto;
use App\Application\Shared\Mapper\User\UserMapper;
use App\Application\Shared\Service\GenerateRandomPasswordInterface;
use App\Application\Shared\Service\SendEmailForNewUserInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Error\EmailAlreadyInUseError;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Enums\PermissionType;

class CreateUserHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserMapper $userMapper,
        private readonly GenerateRandomPasswordInterface $generateRandomPassword,
        private readonly SendEmailForNewUserInterface $sendEmailForNewUser,
    ) {}

    public function __invoke(CreateUserCommand $createUserCommand): UserDto
    {
        $createUserCommand->loggedUser->checkSuperPermission(PermissionType::ADMIN);

        if (!is_null($this->userRepository->getByEmail($createUserCommand->email))) {
            throw new EmailAlreadyInUseError();
        }

        $user = new User(
            $createUserCommand->uuid,
            $createUserCommand->email,
            null,
            null,
            $createUserCommand->firstName,
            $createUserCommand->lastName,
        );

        $password = $this->generateRandomPassword->__invoke($user);

        $user = $this->userRepository->save($user->setPassword($password->encrypted));

        $this->sendEmailForNewUser->__invoke($user, $password->decrypted);

        return $this->userMapper->map($user);
    }
}
