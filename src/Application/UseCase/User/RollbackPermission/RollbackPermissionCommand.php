<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\RollbackPermission;

use App\Application\Shared\Command\CommandInterface;

class RollbackPermissionCommand implements CommandInterface
{
    private string $uuidOfUserWhoRevokePermissions;
    private string $permissionUuid;

    /**
     * RollbackPermissionCommand constructor.
     * @param string $uuidOfUserWhoRevokePermissions
     * @param string $permissionUuid
     */
    public function __construct(string $uuidOfUserWhoRevokePermissions, string $permissionUuid)
    {
        $this->uuidOfUserWhoRevokePermissions = $uuidOfUserWhoRevokePermissions;
        $this->permissionUuid = $permissionUuid;
    }

    /**
     * @return string
     */
    public function getUuidOfUserWhoRevokePermissions(): string
    {
        return $this->uuidOfUserWhoRevokePermissions;
    }

    /**
     * @return string
     */
    public function getPermissionUuid(): string
    {
        return $this->permissionUuid;
    }
}
