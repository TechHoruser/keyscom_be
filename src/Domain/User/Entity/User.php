<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Application\Shared\Dto\Client\ClientDto;
use App\Domain\Client\Entity\Client;
use App\Domain\Machine\Entity\Machine;
use App\Domain\Project\Entity\Project;
use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Tenant\CertainTenant\TenantEntityTrait;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

class User
{
    use AuditableEntityTrait;
    use TenantEntityTrait;

    protected string $uuid;
    protected Collection $permissions;

    public function __construct(
        ?string $uuid,
        protected string $email,
        protected ?string $password,
        protected ?string $pubKey,
        protected string $name,
        /** @var Permission[] $permissions */
        array $permissions = [],
    )
    {
        $this->uuid = $uuid ?? Uuid::uuid4()->toString();
        $this->permissions = new ArrayCollection($permissions);
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPubKey(): ?string
    {
        return $this->pubKey;
    }

    public function setPubKey(?string $pubKey): static
    {
        $this->pubKey = $pubKey;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     *
     * @return Permission[]
     */
    public function getPermissions(): array
    {
        return $this->permissions->getValues();
    }

    /**
     * @param Permission[] $permissions
     *
     * @return $this
     */
    public function setPermissions(array $permissions): static
    {
        $this->permissions = new ArrayCollection($permissions);

        return $this;
    }

    public function isSuper(?PermissionType $permissionType = null): bool
    {
        foreach ($this->getPermissions() as $permission) {
            if (
                (
                    is_null($permission->getUserPermissionType()) ||
                    $permission->getUserPermissionType() === $permissionType
                ) && (
                    $permission->isSuper()
                )
            ) {
                return true;
            }
        }
        return false;
    }

    public function getPermissionsByRelatedEntity(?PermissionType $permissionType = null): array
    {
        $permissions = [];
        foreach (PermissionRelatedEntity::cases() as $case) {
            $permissions[$case->name] = [];
        }

        foreach ($this->getPermissions() as $permission) {
            if (
                !$permission->isSuper() &&
                (
                    is_null($permission->getUserPermissionType()) ||
                    $permission->getUserPermissionType() === $permissionType
                )
            ) {
                $permissions[$permission->getRelatedEntity()->name][] = $permission->getRelatedEntityUuid();
            }
        }

        return $permissions;
    }

    public function hasPermissionForClient(Client $client, ?PermissionType $permissionType = null): bool
    {
        foreach ($this->getPermissions() as $permission) {
            if (
                (
                    is_null($permission->getUserPermissionType()) ||
                    $permission->getUserPermissionType() === $permissionType
                ) && (
                    $permission->isSuper() ||
                    (
                        $permission->getRelatedEntity() === PermissionRelatedEntity::CLIENT &&
                        $permission->getRelatedEntityUuid() === $client->getUuid()
                    )
                )
            ) {
                return true;
            }
        }
        return false;
    }

    public function hasPermissionForProject(Project $project, ?PermissionType $permissionType = null): bool
    {
        foreach ($this->getPermissions() as $permission) {
            if (
                (
                    is_null($permission->getUserPermissionType()) ||
                    $permission->getUserPermissionType() === $permissionType
                ) && (
                    $permission->isSuper()||
                    (
                        $permission->getRelatedEntity() === PermissionRelatedEntity::CLIENT &&
                        $permission->getRelatedEntityUuid() === $project->getClient()->getUuid()
                    ) ||
                    (
                        $permission->getRelatedEntity() === PermissionRelatedEntity::PROJECT &&
                        $permission->getRelatedEntityUuid() === $project->getUuid()
                    )
                )
            ) {
                return true;
            }
        }
        return false;
    }

    public function hasPermissionForMachine(Machine $machine, ?PermissionType $permissionType = null): bool
    {
        foreach ($this->getPermissions() as $permission) {
            if (
                (
                    is_null($permission->getUserPermissionType()) ||
                    $permission->getUserPermissionType() === $permissionType
                ) && (
                    $permission->isSuper() ||
                    (
                        $permission->getRelatedEntity() === PermissionRelatedEntity::CLIENT &&
                        $permission->getRelatedEntityUuid() === $machine->getProject()->getClient()->getUuid()
                    ) ||
                    (
                        $permission->getRelatedEntity() === PermissionRelatedEntity::PROJECT &&
                        $permission->getRelatedEntityUuid() === $machine->getProject()->getUuid()
                    ) ||
                    (
                        $permission->getRelatedEntity() === PermissionRelatedEntity::MACHINE &&
                        $permission->getRelatedEntityUuid() === $machine->getUuid()
                    )
                )
            ) {
                return true;
            }
        }
        return false;
    }
}
