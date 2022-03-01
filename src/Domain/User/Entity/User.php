<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Tenant\CertainTenant\TenantEntityTrait;
use Ramsey\Uuid\Uuid;

class User
{
    use AuditableEntityTrait;
    use TenantEntityTrait;

    private string $uuid;

    public function __construct(
        ?string $uuid,
        private string $email,
        private ?string $pubKey,
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

    public function getPubKey(): ?string
    {
        return $this->pubKey;
    }

    public function setPubKey(?string $pubKey): static
    {
        $this->pubKey = $pubKey;

        return $this;
    }
}
