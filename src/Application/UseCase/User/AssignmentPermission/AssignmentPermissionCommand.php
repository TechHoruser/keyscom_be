<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\AssignmentPermission;

use App\Application\Shared\Command\CommandInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;

class AssignmentPermissionCommand implements CommandInterface
{
    public function __construct(
        readonly User $loggedUser,
        readonly string $userUuid,
        readonly PermissionType $userPermissionType,
        readonly ?PermissionRelatedEntity $typeRelatedEntity,
        readonly ?string $typeOfMachine,
        readonly ?string $relatedEntityUuid,
    ) {}
}
