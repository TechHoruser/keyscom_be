<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\User;

use App\Application\Shared\Dto\User\UserDto;
use App\Application\Shared\Mapper\MapperInterface;
use App\Domain\User\Entity\User;

class UserMapper implements MapperInterface
{
    /**
     * @param User[] $users
     * @return UserDto[]
     */
    public function map($users): iterable
    {
        $usersDto = [];

        foreach ($users as $user) {
            $usersDto[] = new UserDto(
                $user->getUuid(),
                $user->getEmail()
            );
        }

        return $usersDto;
    }
}
