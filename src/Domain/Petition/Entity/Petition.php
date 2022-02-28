<?php

declare(strict_types=1);

namespace App\Domain\Petition\Entity;

use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Shared\Entities\AbstractEntity;
use App\Domain\Tenant\CertainTenant\TenantEntityTrait;

class Petition extends AbstractEntity
{
    use AuditableEntityTrait;
    use TenantEntityTrait;
}
