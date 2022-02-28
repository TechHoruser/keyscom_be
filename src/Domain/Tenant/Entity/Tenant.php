<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Entity;

use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Shared\Entities\AbstractEntity;

class Tenant extends AbstractEntity
{
    use AuditableEntityTrait;

    public function __construct(
        private string $host,
        private TenantInfrastructureConfiguration $infrastructureConfiguration,
    ) {}

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): Tenant
    {
        $this->host = $host;

        return $this;
    }

    public function getInfrastructureConfiguration(): TenantInfrastructureConfiguration
    {
        return $this->infrastructureConfiguration;
    }

    public function setInfrastructureConfiguration(
        TenantInfrastructureConfiguration $infrastructureConfiguration
    ): Tenant {
        $this->infrastructureConfiguration = $infrastructureConfiguration;

        return $this;
    }
}
