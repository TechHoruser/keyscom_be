<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\Permission;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;

interface PermissionRepositoryInterface
{
    public function getByUuid(string $uuid): ?Permission;

    public function permissionsOfUser(string $userUuid): iterable;

    /**
     * @param string $userUuid
     * @param PermissionType $userPermissionType
     * @param PermissionRelatedEntity|null $typeRelatedEntity
     * @param string|null $typeOfMachine
     * @param string|null $relatedEntityUuid
     *
     * @return Permission[]
     */
    public function getChildPermissionsOfUser(
        string $userUuid,
        PermissionType $userPermissionType,
        ?PermissionRelatedEntity $typeRelatedEntity,
        ?string $typeOfMachine,
        ?string $relatedEntityUuid
    ): iterable;

    public function getParentOrSamePermissionOfUser(
        string $userUuid,
        PermissionType $userPermissionType,
        ?PermissionRelatedEntity $typeRelatedEntity,
        ?string $typeOfMachine,
        ?string $relatedEntityUuid
    ): ?Permission;

    /**
     * @param Permission $permission
     * @return Permission
     */
    public function save(Permission $permission): Permission;
}
