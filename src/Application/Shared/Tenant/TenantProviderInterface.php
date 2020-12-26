<?php

declare(strict_types=1);

namespace App\Application\Shared\Tenant;

use App\Domain\Tenant\Entity\TenantInfrastructureConfiguration;

interface TenantProviderInterface
{
    public function getTenantConfiguration(string $host): ?TenantInfrastructureConfiguration;
}
