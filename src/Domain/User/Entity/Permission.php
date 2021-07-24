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
    private User $user;
    /** @var string('admin','ssh') $userPermissionType */
    private string $userPermissionType;
    /** @var null|string('machine', 'project', 'client') $relatedEntity */
    private ?string $relatedEntity;
    private ?string $typeOfMachine;
    private ?string $relatedEntityUuid;

    public const PERMISSION_ADMIN = 'admin';
    public const PERMISSION_SSH = 'ssh';

    /**
     * Permission constructor.
     * @param string|null $uuid
     * @param User $user
     * @param string $userPermissionType
     * @param string|null $relatedEntity
     * @param string|null $typeOfMachine
     * @param string|null $relatedEntityUuid
     */
    public function __construct(
        ?string $uuid,
        User $user,
        string $userPermissionType,
        ?string $relatedEntity,
        ?string $typeOfMachine,
        ?string $relatedEntityUuid
    ) {
        $this->uuid = $uuid ?? Uuid::uuid4()->toString();
        $this->user = $user;
        $this->userPermissionType = $userPermissionType;
        $this->relatedEntity = $relatedEntity;
        $this->typeOfMachine = $typeOfMachine;
        $this->relatedEntityUuid = $relatedEntityUuid;
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
    public function getFilterTypeOfMachine(): ?string
    {
        return $this->typeOfMachine;
    }

    /**
     * @param string|null $typeOfMachine
     */
    public function setFilterTypeOfMachine(?string $typeOfMachine): void
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
}
