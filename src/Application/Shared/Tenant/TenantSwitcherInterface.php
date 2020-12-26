<?php

namespace App\Application\Shared\Tenant;

use App\Domain\Tenant\Entity\TenantInfrastructureConfiguration;

interface TenantSwitcherInterface
{
    public function switch(TenantInfrastructureConfiguration $infrastructureConfiguration): void;
}
