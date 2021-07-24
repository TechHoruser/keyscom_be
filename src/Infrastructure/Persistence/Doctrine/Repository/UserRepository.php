<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    protected function getAliasTable(): string
    {
        return 'users';
    }

    protected function getEntityRepositoryClass(): string
    {
        return User::class;
    }
}
