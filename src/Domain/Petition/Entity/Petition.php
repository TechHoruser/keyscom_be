<?php

declare(strict_types=1);

namespace App\Domain\Petition\Entity;

use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Tenant\CertainTenant\TenantEntityTrait;

class Petition
{
    use AuditableEntityTrait;
    use TenantEntityTrait;
}
