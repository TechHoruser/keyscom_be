<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Shared\Entities\PaginationProperties;
use App\Domain\Shared\Interfaces\ComplexFindInterface;
use App\Domain\User\Entity\User;

interface UserRepositoryInterface extends ComplexFindInterface
{
    public function getByEmail(string $email): ?User;

    public function getByUuid(string $uuid): ?User;

    /**
     * @param PaginationProperties $paginationProperties
     * @param string[] $embeds
     * @param string[] $filtersWithAnds
     * @param string[] $filtersWithOrs
     *
     * @return User[]
     */
    public function complexFind(
        PaginationProperties $paginationProperties = new PaginationProperties(),
        array $embeds = [],
        array $filtersWithAnds = [],
        array $filtersWithOrs = [],
    ): iterable;

    public function save(User $user): User;
}
