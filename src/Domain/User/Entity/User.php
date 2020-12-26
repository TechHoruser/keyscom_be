<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Tenant\CertainTenant\TenantEntityTrait;

class User
{
    use AuditableEntityTrait;
    use TenantEntityTrait;

    private ?string $pubKey;

    /**
     * User constructor.
     * @param string|null $pubKey
     */
    public function __construct(?string $pubKey)
    {
        $this->pubKey = $pubKey;
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
