<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Machine\Entity\Machine;
use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Tenant\CertainTenant\TenantEntityTrait;
use Ramsey\Uuid\Uuid;

class ActionUserOnMachine
{
    use AuditableEntityTrait;
    use TenantEntityTrait;

    private string $uuid;
    private Permission $permission;
    private Machine $machine;
    private string $actionToDo;
    private bool $processed;
    private bool $canceled;

    public const ACTION_ADD = 'add';
    public const ACTION_REMOVE = 'remove';

    /**
     * ActionUserOnMachine constructor.
     * @param null|string $uuid
     * @param Permission $permission
     * @param Machine $machine
     * @param string $actionToDo
     */
    public function __construct(
        ?string $uuid,
        Permission $permission,
        Machine $machine,
        string $actionToDo
    ) {
        $this->uuid = $uuid ?? Uuid::uuid4()->toString();
        $this->permission = $permission;
        $this->machine = $machine;
        $this->actionToDo = $actionToDo;
        $this->processed = false;
        $this->canceled = false;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return Permission
     */
    public function getPermission(): Permission
    {
        return $this->permission;
    }

    /**
     * @param Permission $permission
     */
    public function setPermission(Permission $permission): void
    {
        $this->permission = $permission;
    }

    /**
     * @return Machine
     */
    public function getMachine(): Machine
    {
        return $this->machine;
    }

    /**
     * @param Machine $machine
     */
    public function setMachine(Machine $machine): void
    {
        $this->machine = $machine;
    }

    /**
     * @return string
     */
    public function getActionToDo(): string
    {
        return $this->actionToDo;
    }

    /**
     * @param string $actionToDo
     */
    public function setActionToDo(string $actionToDo): void
    {
        $this->actionToDo = $actionToDo;
    }

    /**
     * @return bool
     */
    public function isProcessed(): bool
    {
        return $this->processed;
    }

    /**
     * @param bool $processed
     */
    public function setProcessed(bool $processed): void
    {
        $this->processed = $processed;
    }

    /**
     * @return bool
     */
    public function isCanceled(): bool
    {
        return $this->canceled;
    }

    /**
     * @param bool $canceled
     */
    public function setCanceled(bool $canceled): void
    {
        $this->canceled = $canceled;
    }
}
