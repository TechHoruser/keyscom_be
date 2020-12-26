<?php

declare(strict_types=1);

namespace App\Infrastructure\Tenant\Repository;

use App\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;
use App\Infrastructure\Tenant\Entity\TenantConfiguration;

class TenantConfigurationRepository extends AbstractRepository
{
    protected function getAliasTable(): string
    {
        return 'tenant_infrastructure_configuration';
    }

    protected function getEntityRepositoryClass(): string
    {
        return TenantConfiguration::class;
    }

    public function findByHost(string $host): ?TenantConfiguration
    {
        /** @var TenantConfiguration $tenantConfiguration */
        $tenantConfiguration = $this->findOneBy(['host' => $host]);
        return $tenantConfiguration;
    }
}
