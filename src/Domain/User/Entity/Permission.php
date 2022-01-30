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
    private ?User $createdBy;
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
        ?User $createdBy,
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
     *
     * @return $this
     */
    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    /**
     * @param User|null $createdBy
     *
     * @return $this
     */
    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
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
     *
     * @return $this
     */
    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return PermissionType
     */
    public function getUserPermissionType(): PermissionType
    {
        return $this->userPermissionType;
    }

    /**
     * @param PermissionType $userPermissionType
     *
     * @return $this
     */
    public function setUserPermissionType(PermissionType $userPermissionType): static
    {
        $this->userPermissionType = $userPermissionType;

        return $this;
    }

    /**
     * @return PermissionRelatedEntity|null
     */
    public function getRelatedEntity(): ?PermissionRelatedEntity
    {
        return $this->relatedEntity;
    }

    /**
     * @param PermissionRelatedEntity|null $relatedEntity
     *
     * @return $this
     */
    public function setRelatedEntity(?PermissionRelatedEntity $relatedEntity): static
    {
        $this->relatedEntity = $relatedEntity;

        return $this;
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
     *
     * @return $this
     */
    public function setTypeOfMachine(?string $typeOfMachine): static
    {
        $this->typeOfMachine = $typeOfMachine;

        return $this;
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
     *
     * @return $this
     */
    public function setRelatedEntityUuid(?string $relatedEntityUuid): static
    {
        $this->relatedEntityUuid = $relatedEntityUuid;

        return $this;
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
     *
     * @return $this
     */
    public function setReverted(bool $reverted): static
    {
        $this->reverted = $reverted;

        return $this;
    }

    /**
     * @return ActionUserOnMachine[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @param ActionUserOnMachine[] $actions
     *
     * @return $this
     */
    public function setActions(array $actions): static
    {
        $this->actions = $actions;

        return $this;
    }
}
