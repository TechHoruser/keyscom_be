<?php

declare(strict_types=1);

namespace App\Domain\Tenant\CertainTenant;

use App\Domain\Tenant\Entity\Tenant;

trait TenantEntityTrait
{
    protected ?string $tenantName = null;

    /**
     * @param Tenant|null $tenant
     * @return $this
     */
    public function setTenant(?Tenant $tenant): self
    {
        $this->tenantName = $tenant->getInfrastructureConfiguration()->getName();

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTenantName(): ?string
    {
        return $this->tenantName;
    }

    /**
     * @param string|null $tenantName
     */
    public function setTenantName(?string $tenantName): void
    {
        $this->tenantName = $tenantName;
    }
}
