<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Entity;

interface TenantInfrastructureConfiguration
{
    public function getName(): string;
}
