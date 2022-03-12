<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Tenant\CertainTenant\TenantEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

class User
{
    use AuditableEntityTrait;
    use TenantEntityTrait;

    protected string $uuid;

    public function __construct(
        ?string $uuid,
        protected string $email,
        protected ?string $password,
        protected ?string $pubKey,
        protected string $name,
        /** @var Permission[] $permissions */
        protected Collection $permissions = new ArrayCollection(),
    )
    {
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

    public function getPermissions(): ArrayCollection|Collection
    {
        return $this->permissions;
    }

    public function setPermissions(ArrayCollection|Collection $permissions): static
    {
        $this->permissions = $permissions;

        return $this;
    }
}
