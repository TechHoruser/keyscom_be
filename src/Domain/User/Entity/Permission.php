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
    private User $createdBy;
    private User $user;
    private PermissionType $userPermissionType;
    private ?PermissionRelatedEntity $relatedEntity;
    private ?string $typeOfMachine;
    private ?string $relatedEntityUuid;
    private bool $reverted;

    /** @var ActionUserOnMachine[] $actions */
    private iterable $actions;

    public function __construct(
        ?string $uuid,
        User $createdBy,
        User $user,
        PermissionType $userPermissionType,
        ?PermissionRelatedEntity $relatedEntity,
        ?string $typeOfMachine,
        ?string $relatedEntityUuid
    ) {
        $this->uuid = $uuid ?? Uuid::uuid4()->toString();
        $this->createdBy = $createdBy;
        $this->user = $user;
        $this->userPermissionType = $userPermissionType;
        $this->relatedEntity = $relatedEntity;
        $this->typeOfMachine = $typeOfMachine;
        $this->relatedEntityUuid = $relatedEntityUuid;
        $this->reverted = false;
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
     * @return User
     */
    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     */
    public function setCreatedBy(User $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUserPermissionType(): PermissionType
    {
        return $this->userPermissionType;
    }

    public function setUserPermissionType(PermissionType $userPermissionType): void
    {
        $this->userPermissionType = $userPermissionType;
    }

    public function getRelatedEntity(): ?PermissionRelatedEntity
    {
        return $this->relatedEntity;
    }

    public function setRelatedEntity(?PermissionRelatedEntity $relatedEntity): void
    {
        $this->relatedEntity = $relatedEntity;
    }

    /**
     * @return string|null
     */
    public function getTypeOfMachine(): ?string
    {
        return $this->typeOfMachine;
    }

    /**
     * @param string|null $typeOfMachine
     */
    public function setTypeOfMachine(?string $typeOfMachine): void
    {
        $this->typeOfMachine = $typeOfMachine;
    }

    /**
     * @return string|null
     */
    public function getRelatedEntityUuid(): ?string
    {
        return $this->relatedEntityUuid;
    }

    /**
     * @param string|null $relatedEntityUuid
     */
    public function setRelatedEntityUuid(?string $relatedEntityUuid): void
    {
        $this->relatedEntityUuid = $relatedEntityUuid;
    }

    /**
     * @param ActionUserOnMachine $action
     */
    public function addAction(ActionUserOnMachine $action): void
    {
        $this->actions[] = $action;
    }

    /**
     * @return ActionUserOnMachine[]
     */
    public function getActions(): iterable
    {
        return $this->actions;
    }

    /**
     * @param ActionUserOnMachine[] $actions
     */
    public function setActions(iterable $actions): void
    {
        $this->actions = $actions;
    }

    /**
     * @return bool
     */
    public function isReverted(): bool
    {
        return $this->reverted;
    }

    /**
     * @param bool $reverted
     */
    public function setReverted(bool $reverted): void
    {
        $this->reverted = $reverted;
    }
}
