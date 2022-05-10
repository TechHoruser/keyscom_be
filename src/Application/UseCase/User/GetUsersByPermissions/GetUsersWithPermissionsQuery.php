<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\GetUsersByPermissions;

use App\Application\Shared\Query\QueryInterface;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;

class GetUsersWithPermissionsQuery implements QueryInterface
{
    public function __construct(
        readonly ?PermissionType $permissionType,
        readonly PermissionRelatedEntity $relatedEntity,
        readonly string $entityUuid,
    ) {}
}
