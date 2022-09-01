<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\GetUser;

use App\Application\Shared\Dto\User\UserDto;
use App\Application\Shared\Mapper\User\UserMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Shared\Errors\NotFoundError;
use App\Domain\User\Repository\UserRepositoryInterface;

class GetUserHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserMapper $userMapper,
    ) {}

    public function __invoke(GetUserQuery $getUserQuery): UserDto
    {
        $user = $this->userRepository->getByUuid(
            $getUserQuery->uuid,
            $getUserQuery->embeds,
        ) ?? throw new NotFoundError('Bad User Uuid');

        return $this->userMapper->map($user);
    }
}
