<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Tenant\Entity\Tenant;
use App\Domain\Tenant\Repository\TenantRepositoryInterface;

class TenantRepository extends AbstractRepository implements TenantRepositoryInterface
{
    protected function getAliasTable(): string
    {
        return 'tenants';
    }

    protected function getEntityRepositoryClass(): string
    {
        return Tenant ::class;
    }

    public function findByHost(string $host): ?Tenant
    {
        return $this->findOneBy(['host' => $host]);
    }
}
