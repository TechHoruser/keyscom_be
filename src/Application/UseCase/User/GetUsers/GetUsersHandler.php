<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\GetUsers;

use App\Application\Shared\Dto\Pagination\PaginationDto;
use App\Application\Shared\Mapper\Pagination\PaginationMapper;
use App\Application\Shared\Mapper\User\UserMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;

class GetUsersHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PaginationMapper $paginationMapper,
        private readonly UserMapper $userMapper,
    ) {}

    public function __invoke(GetUsersQuery $getUsersQuery): PaginationDto
    {
        /** @var User[] $results */
        $results = $this->userRepository->complexFind(
            $getUsersQuery->paginationProperties,
            [],
            $getUsersQuery->filters,
        );

        return $this->paginationMapper->map(
            $this->userMapper->mapArray($results),
            $this->userRepository->countAll($getUsersQuery->filters)
        );
    }
}
