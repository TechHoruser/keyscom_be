<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\User;

use App\Application\Shared\Dto\User\UserDto;
use App\Application\Shared\Mapper\AbstractMapper;
use App\Domain\User\Entity\User;

class UserMapper extends AbstractMapper
{
    /**
     * @param User $user
     * @return UserDto
     */
    public function map(User $user): UserDto
    {
        return new UserDto(
            $user->getUuid(),
            $user->getEmail(),
            $user->getFirstName(),
            $user->getLastName(),
        );
    }

    /**
     * @param User[] $users
     * @param string[] $embeds
     *
     * @return UserDto[]
     */
    public function mapArray(array $users, array $embeds = []): array
    {
        return parent::mapArray($users, $embeds);
    }
}
