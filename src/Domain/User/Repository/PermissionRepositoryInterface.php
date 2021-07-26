<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\Permission;

interface PermissionRepositoryInterface
{
    public function getByUuid(string $uuid): ?Permission;

    public function permissionsOfUser(string $userUuid): iterable;

    /**
     * @param string $userUuid
     * @param string('admin','ssh') $userType
     * @param null|string('machine', 'project', 'client') $relatedEntity
     * @param string|null $typeOfMachine
     * @param string|null $relatedEntityUuid
     * @return iterable
     */
    public function getChildPermissionsOfUser(
        string $userUuid,
        string $userPermissionType,
        ?string $typeRelatedEntity,
        ?string $typeOfMachine,
        ?string $relatedEntityUuid
    ): iterable;

    /**
     * @param string $userUuid
     * @param string('admin','ssh') $userType
     * @param null|string('machine', 'project', 'client') $relatedEntity
     * @param string|null $typeOfMachine
     * @param string|null $relatedEntityUuid
     * @return Permission|null
     */
    public function getParentOrSamePermissionOfUser(
        string $userUuid,
        string $userPermissionType,
        ?string $typeRelatedEntity,
        ?string $typeOfMachine,
        ?string $relatedEntityUuid
    ): ?Permission;

    /**
     * @param Permission $permission
     * @return Permission
     */
    public function save(Permission $permission): Permission;
}
