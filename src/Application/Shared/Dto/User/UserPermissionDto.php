<?php

declare(strict_types=1);

namespace App\Application\Shared\Dto\User;

class UserPermissionDto
{
    public function __construct(
        readonly string $uuid,
        readonly UserDto $user,
        readonly ?string $permissionRelatedEntity,
        readonly ?string $permissionRelatedEntityUuid,
        readonly ?string $userPermissionType,
    ) {}
}
