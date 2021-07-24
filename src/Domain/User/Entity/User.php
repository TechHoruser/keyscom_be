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
    private string $email;
    private ?string $pubKey;

    /**
     * User constructor.
     * @param string|null $uuid
     * @param string $email
     * @param string|null $pubKey
     */
    public function __construct(?string $uuid, string $email, ?string $pubKey)
    {
        $this->uuid = $uuid ?? Uuid::uuid4()->toString();
        $this->email = $email;
        $this->pubKey = $pubKey;
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
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPubKey(): ?string
    {
        return $this->pubKey;
    }

    /**
     * @param string|null $pubKey
     */
    public function setPubKey(?string $pubKey): void
    {
        $this->pubKey = $pubKey;
    }
}
