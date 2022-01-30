<?php

declare(strict_types=1);

namespace App\Domain\Client\Entity;

use App\Domain\Project\Entity\Project;
use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Tenant\CertainTenant\TenantEntityTrait;
use Ramsey\Uuid\Uuid;

class Client
{
    use AuditableEntityTrait;
    use TenantEntityTrait;

    private string $uuid;

    public function __construct(?string $uuid,
        private string $name,
        /** @var Project[] $projects */
        private iterable $projects = [],
    ) {
        $this->uuid = $uuid ?? Uuid::uuid4()->toString();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getProjects(): iterable
    {
        return $this->projects;
    }

    public function setProjects(iterable $projects): static
    {
        $this->projects = $projects;

        return $this;
    }
}
