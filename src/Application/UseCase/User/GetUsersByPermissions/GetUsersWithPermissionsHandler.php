<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\GetUsersByPermissions;

use App\Application\Shared\Dto\Pagination\PaginationDto;
use App\Application\Shared\Dto\User\UserPermissionDto;
use App\Application\Shared\Mapper\User\UserPermissionMapper;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\User\Repository\PermissionRepositoryInterface;

class GetUsersWithPermissionsHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly PermissionRepositoryInterface $permissionRepository,
        private readonly UserPermissionMapper $mapper,
    ) {}

    /**
     * @return UserPermissionDto[]
     */
    public function __invoke(GetUsersWithPermissionsQuery $getUsersWithPermissionsQuery): array
    {
        $permissions = array_merge(
            $this->permissionRepository->getChildPermissionsOfUser(
                null,
                $getUsersWithPermissionsQuery->permissionType,
                $getUsersWithPermissionsQuery->relatedEntity,
                null,
                $getUsersWithPermissionsQuery->entityUuid,
            ),
            $this->permissionRepository->getParentOrSamePermissionOfUser(
                null,
                $getUsersWithPermissionsQuery->permissionType,
                $getUsersWithPermissionsQuery->relatedEntity,
                null,
                $getUsersWithPermissionsQuery->entityUuid,
            ),
        );

        return $this->mapper->mapArray($permissions, ['user']);
    }
}
