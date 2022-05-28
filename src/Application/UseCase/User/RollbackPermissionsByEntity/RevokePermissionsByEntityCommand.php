<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\RollbackPermissionsByEntity;

use App\Application\Shared\Command\CommandInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;

class RevokePermissionsByEntityCommand implements CommandInterface
{
    public function __construct(
        readonly User $loggedUser,
        readonly string $userToRevokePermissionUuid,
        readonly ?PermissionType $permissionType,
        readonly ?PermissionRelatedEntity $entity,
        readonly ?string $entityUuid,
    ) {}
}
