<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\Error\EmailAlreadyInUseError;
use App\Domain\User\Repository\UserRepositoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

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

    public function getByEmail(string $email, array $embeds = []): ?User
    {
        return $this->getOneOrNullByComplexFind(
            $embeds,
            [
                'email' => $email,
            ],
        );
    }

    public function getByUuid(string $uuid, array $embeds = []): ?User
    {
        return parent::getByUuid($uuid, $embeds);
    }

    public function save(User $user): User
    {
        try {
            return parent::saveEntity($user);
        } catch (UniqueConstraintViolationException) {
            throw new EmailAlreadyInUseError();
        }
    }
}
