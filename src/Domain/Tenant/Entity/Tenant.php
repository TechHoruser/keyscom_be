<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Entity;

use App\Domain\Shared\Auditable\AuditableEntityTrait;

class Tenant
{
    use AuditableEntityTrait;

    private string $host;
    private TenantInfrastructureConfiguration $infrastructureConfiguration;

    /**
     * Tenant constructor.
     * @param string $host
     * @param TenantInfrastructureConfiguration $infrastructureConfiguration
     */
    public function __construct(string $host, TenantInfrastructureConfiguration $infrastructureConfiguration)
    {
        $this->host = $host;
        $this->infrastructureConfiguration = $infrastructureConfiguration;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function setHost(string $host): Tenant
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return TenantInfrastructureConfiguration
     */
    public function getInfrastructureConfiguration(): TenantInfrastructureConfiguration
    {
        return $this->infrastructureConfiguration;
    }

    /**
     * @param TenantInfrastructureConfiguration $infrastructureConfiguration
     * @return $this
     */
    public function setInfrastructureConfiguration(
        TenantInfrastructureConfiguration $infrastructureConfiguration
    ): Tenant {
        $this->infrastructureConfiguration = $infrastructureConfiguration;

        return $this;
    }
}
