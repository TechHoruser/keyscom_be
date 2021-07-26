<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Tenant\CertainTenant\TenantEntityTrait;
use Ramsey\Uuid\Uuid;

class Permission
{
    use AuditableEntityTrait;
    use TenantEntityTrait;

    private string $uuid;
    private User $createdBy;
    private User $user;
    /** @var string('admin','ssh') $userPermissionType */
    private string $userPermissionType;
    /** @var null|string('machine', 'project', 'client') $relatedEntity */
    private ?string $relatedEntity;
    private ?string $typeOfMachine;
    private ?string $relatedEntityUuid;
    private bool $reverted;

    /** @var ActionUserOnMachine[] $actions */
    private iterable $actions;

    public const PERMISSION_ADMIN = 'admin';
    public const PERMISSION_SSH = 'ssh';

    /**
     * Permission constructor.
     * @param string|null $uuid
     * @param User $created
     *By
     * @param User $user
     * @param string $userPermissionType
     * @param string|null $relatedEntity
     * @param string|null $typeOfMachine
     * @param string|null $relatedEntityUuid
     */
    public function __construct(
        ?string $uuid,
        User $createdBy,
        User $user,
        string $userPermissionType,
        ?string $relatedEntity,
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

    /**
     * @return string
     */
    public function getUserPermissionType(): string
    {
        return $this->userPermissionType;
    }

    /**
     * @param string $userPermissionType
     */
    public function setUserPermissionType(string $userPermissionType): void
    {
        $this->userPermissionType = $userPermissionType;
    }

    /**
     * @return string|null
     */
    public function getRelatedEntity(): ?string
    {
        return $this->relatedEntity;
    }

    /**
     * @param string|null $relatedEntity
     */
    public function setRelatedEntity(?string $relatedEntity): void
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
