<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Tenant\CertainTenant\TenantEntityTrait;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use Ramsey\Uuid\Uuid;

class Permission
{
    use AuditableEntityTrait;
    use TenantEntityTrait;

    private string $uuid;

    public function __construct(
        ?string $uuid,
        private ?User $createdBy,
        private User $user,
        private PermissionType $userPermissionType,
        private ?PermissionRelatedEntity $relatedEntity,
        private ?string $typeOfMachine,
        private ?string $relatedEntityUuid,
        private bool $reverted = false,
        /** @var ActionUserOnMachine[] $actions */
        private iterable $actions = [],
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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getUserPermissionType(): PermissionType
    {
        return $this->userPermissionType;
    }

    public function setUserPermissionType(PermissionType $userPermissionType): static
    {
        $this->userPermissionType = $userPermissionType;

        return $this;
    }

    public function getRelatedEntity(): ?PermissionRelatedEntity
    {
        return $this->relatedEntity;
    }

    public function setRelatedEntity(?PermissionRelatedEntity $relatedEntity): static
    {
        $this->relatedEntity = $relatedEntity;

        return $this;
    }

    public function getTypeOfMachine(): ?string
    {
        return $this->typeOfMachine;
    }

    public function setTypeOfMachine(?string $typeOfMachine): static
    {
        $this->typeOfMachine = $typeOfMachine;

        return $this;
    }

    public function getRelatedEntityUuid(): ?string
    {
        return $this->relatedEntityUuid;
    }

    public function setRelatedEntityUuid(?string $relatedEntityUuid): static
    {
        $this->relatedEntityUuid = $relatedEntityUuid;

        return $this;
    }

    public function isReverted(): bool
    {
        return $this->reverted;
    }

    public function setReverted(bool $reverted): static
    {
        $this->reverted = $reverted;

        return $this;
    }

    public function getActions(): iterable
    {
        return $this->actions;
    }

    public function setActions(iterable $actions): static
    {
        $this->actions = $actions;

        return $this;
    }
}
