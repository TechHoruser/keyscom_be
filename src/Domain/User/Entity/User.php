<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Client\Entity\Client;
use App\Domain\Machine\Entity\Machine;
use App\Domain\Project\Entity\Project;
use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Shared\Exception\ForbiddenException;
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
        protected string $firstName,
        protected string $lastName,
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

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

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

    public function checkSuperPermission(?PermissionType $permissionType = null): void
    {
        if (!$this->isSuper($permissionType)) {
            throw new ForbiddenException();
        }
    }

    public function isSuper(?PermissionType $permissionType = null): bool
    {
        foreach ($this->getPermissions() as $permission) {
            if (
                (
                    is_null($permissionType) ||
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
                    is_null($permissionType) ||
                    $permission->getUserPermissionType() === $permissionType
                )
            ) {
                $permissions[$permission->getRelatedEntity()->name][] = $permission->getRelatedEntityUuid();
            }
        }

        return $permissions;
    }

    public function checkPermissionForClient(Client $client, ?PermissionType $permissionType = null): void
    {
        if (!$this->hasPermissionForClient($client, $permissionType)) {
            throw new ForbiddenException();
        }
    }

    public function hasPermissionForClient(Client $client, ?PermissionType $permissionType = null): bool
    {
        foreach ($this->getPermissions() as $permission) {
            if (
                (
                    is_null($permissionType) ||
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

    public function checkPermissionForProject(Project $project, ?PermissionType $permissionType = null): void
    {
        if (!$this->hasPermissionForProject($project, $permissionType)) {
            throw new ForbiddenException();
        }
    }

    public function hasPermissionForProject(Project $project, ?PermissionType $permissionType = null): bool
    {
        foreach ($this->getPermissions() as $permission) {
            if (
                (
                    is_null($permissionType) ||
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

    public function checkPermissionForMachine(Machine $machine, ?PermissionType $permissionType = null): void
    {
        if (!$this->hasPermissionForMachine($machine, $permissionType)) {
            throw new ForbiddenException();
        }
    }

    public function hasPermissionForMachine(Machine $machine, ?PermissionType $permissionType = null): bool
    {
        foreach ($this->getPermissions() as $permission) {
            if (
                (
                    is_null($permissionType) ||
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

    public function getPermissionsConditions(PermissionRelatedEntity $permissionRelatedEntity): array
    {
        $filterFieldByRelatedEntityType = match ($permissionRelatedEntity) {
            PermissionRelatedEntity::CLIENT => [
                PermissionRelatedEntity::CLIENT->name  => 'uuid',
                PermissionRelatedEntity::PROJECT->name => 'projects.uuid',
                PermissionRelatedEntity::MACHINE->name => 'projects.machines.uuid',
            ],
            PermissionRelatedEntity::PROJECT => [
                PermissionRelatedEntity::CLIENT->name  => 'client.uuid',
                PermissionRelatedEntity::PROJECT->name => 'uuid',
                PermissionRelatedEntity::MACHINE->name => 'machines.uuid',
            ],
            PermissionRelatedEntity::MACHINE => [
                PermissionRelatedEntity::CLIENT->name  => 'project.client.uuid',
                PermissionRelatedEntity::PROJECT->name => 'project.uuid',
                PermissionRelatedEntity::MACHINE->name => 'uuid',
            ],
        };

        $filtersByPermissions = [];

        if(!$this->isSuper()) {
            $permissionsByRelatedEntity = $this->getPermissionsByRelatedEntity();
            foreach ($permissionsByRelatedEntity as $relatedEntity => $uuids) {
                foreach ($uuids as $uuid) {
                    if (!isset($filtersByPermissions[$filterFieldByRelatedEntityType[$relatedEntity]])) {
                        $filtersByPermissions[$filterFieldByRelatedEntityType[$relatedEntity]] = [];
                    }
                    $filtersByPermissions[$filterFieldByRelatedEntityType[$relatedEntity]][] = $uuid;
                }
            }

            if (empty($filtersByPermissions)) {
                throw new ForbiddenException();
            }
        }

        return $filtersByPermissions;
    }
}
