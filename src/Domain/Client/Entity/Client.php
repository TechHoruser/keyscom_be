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
    private string $name;

    /** @var Project[] $projects */
    private iterable $projects;

    /**
     * Client constructor.
     * @param string|null $uuid
     * @param string $name
     */
    public function __construct(?string $uuid, string $name)
    {
        $this->uuid = $uuid ?? Uuid::uuid4()->toString();
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param Project $project
     */
    public function addProject(Project $project): void
    {
        $this->projects[] = $project;
    }

    /**
     * @return Project[]
     */
    public function getProjects(): iterable
    {
        return $this->projects;
    }

    /**
     * @param Project[] $projects
     */
    public function setProjects(iterable $projects): void
    {
        $this->projects = $projects;
    }
}
