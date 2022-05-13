<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\User;

use App\Application\Shared\Dto\User\UserPermissionDto;
use App\Application\Shared\Dto\User\UserDto;
use App\Application\Shared\Mapper\AbstractMapper;
use App\Domain\User\Entity\Permission;

class UserPermissionMapper extends AbstractMapper
{
    /**
     * UserPermissionMapper constructor.
     */
    public function __construct(
        private readonly UserMapper $userMapper,
    ) {}

    /**
     * @param Permission $permission
     * @return UserPermissionDto
     */
    public function map(Permission $permission): UserPermissionDto
    {
        return new UserPermissionDto(
            $permission->getUuid(),
            $this->userMapper->map($permission->getUser()),
            $permission->getRelatedEntity()?->value,
            $permission->getRelatedEntityUuid(),
            $permission->getUserPermissionType()?->value,
        );
    }

    /**
     * @param Permission[] $entities
     * @param string[] $embeds
     *
     * @return UserDto[]
     */
    public function mapArray(array $entities, array $embeds = []): array
    {
        return parent::mapArray($entities, $embeds);
    }
}
