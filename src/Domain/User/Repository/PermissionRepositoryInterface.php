<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Shared\Errors\DomainError;
use App\Domain\User\Entity\Permission;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;

interface PermissionRepositoryInterface
{
    public function getByUuid(string $uuid): ?Permission;

    public function permissionsOfUser(string $userUuid): iterable;

    /**
     * @return Permission[]
     */
    public function getChildPermissionsOfUser(
        ?User $user = null,
        ?PermissionType $userPermissionType = null,
        ?PermissionRelatedEntity $typeRelatedEntity = null,
        ?string $typeOfMachine = null,
        ?string $relatedEntityUuid = null,
    ): array;

    /**
     * @return Permission[]
     */
    public function getParentOrSamePermissionOfUser(
        ?User $user = null,
        ?PermissionType $userPermissionType = null,
        ?PermissionRelatedEntity $typeRelatedEntity = null,
        ?string $typeOfMachine = null,
        ?string $relatedEntityUuid = null,
    ): array;

    public function save(Permission $permission): Permission;
}
