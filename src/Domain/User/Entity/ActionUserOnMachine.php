<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Machine\Entity\Machine;
use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Tenant\CertainTenant\TenantEntityTrait;
use App\Domain\User\Enums\ActionOfUserOnMachine;
use Ramsey\Uuid\Uuid;

class ActionUserOnMachine
{
    use AuditableEntityTrait;
    use TenantEntityTrait;

    private string $uuid;

    public function __construct(
        ?string $uuid,
        private Permission $permission,
        private Machine $machine,
        private ActionOfUserOnMachine $actionToDo,
        private bool $skipped = false,
        private bool $processed = false,
        private bool $canceled = false,
    ) {
        $this->uuid = $uuid ?? Uuid::uuid4()->toString();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getPermission(): Permission
    {
        return $this->permission;
    }

    public function setPermission(Permission $permission): static
    {
        $this->permission = $permission;

        return $this;
    }

    public function getMachine(): Machine
    {
        return $this->machine;
    }

    public function setMachine(Machine $machine): static
    {
        $this->machine = $machine;

        return $this;
    }

    public function getActionToDo(): ActionOfUserOnMachine
    {
        return $this->actionToDo;
    }

    public function setActionToDo(ActionOfUserOnMachine $actionToDo): static
    {
        $this->actionToDo = $actionToDo;

        return $this;
    }

    public function isSkipped(): bool
    {
        return $this->skipped;
    }

    public function setSkipped(bool $skipped): static
    {
        $this->skipped = $skipped;

        return $this;
    }

    public function isProcessed(): bool
    {
        return $this->processed;
    }

    public function setProcessed(bool $processed): static
    {
        $this->processed = $processed;

        return $this;
    }

    public function isCanceled(): bool
    {
        return $this->canceled;
    }

    public function setCanceled(bool $canceled): static
    {
        $this->canceled = $canceled;

        return $this;
    }
}
