<?php

declare(strict_types=1);

namespace App\Infrastructure\Tenant;

use App\Application\Shared\Tenant\TenantProviderInterface;
use App\Domain\Tenant\Entity\TenantInfrastructureConfiguration;
use App\Infrastructure\Tenant\Repository\TenantConfigurationRepository;

class TenantProvider implements TenantProviderInterface
{
    private TenantConfigurationRepository $tenantConfigurationRepository;

    public function __construct(TenantConfigurationRepository $tenantConfigurationRepository)
    {
        $this->tenantConfigurationRepository = $tenantConfigurationRepository;
    }

    public function getTenantConfiguration(string $host): ?TenantInfrastructureConfiguration
    {
        return $this->tenantConfigurationRepository->findByHost($host);
    }
}
