<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Repository;

use App\Domain\Tenant\Entity\Tenant;

interface TenantRepositoryInterface
{
    public function findByHost(string $host): ?Tenant;
}
