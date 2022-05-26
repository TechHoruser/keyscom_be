<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\RollbackPermissionsByEntity;

use App\Application\Shared\Command\CommandInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\PermissionRelatedEntity;

class RollbackPermissionsByEntityCommand implements CommandInterface
{
    public function __construct(
        readonly User $loggedUser,
        readonly ?PermissionRelatedEntity $entity,
        readonly ?string $entityUuid,
    ) {}
}
