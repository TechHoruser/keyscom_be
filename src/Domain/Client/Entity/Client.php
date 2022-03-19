<?php

declare(strict_types=1);

namespace App\Domain\Client\Entity;

use App\Domain\Project\Entity\Project;
use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Tenant\CertainTenant\TenantEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

class Client
{
    use AuditableEntityTrait;
    use TenantEntityTrait;

    private string $uuid;

    public function __construct(
        ?string $uuid,
        private string $name,
        private Collection $projects = new ArrayCollection(),
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

    /**
     *
     * @return Project[]
     */
    public function getProjects(): array
    {
        return $this->projects->getValues();
    }

    /**
     * @param Project[] $projects
     *
     * @return $this
     */
    public function setProjects(array $projects): static
    {
        $this->projects = new ArrayCollection($projects);

        return $this;
    }

    public function addProject(Project $project): static
    {
        $this->projects->add($project);

        return $this;
    }
}
