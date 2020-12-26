<?php

declare(strict_types=1);

namespace App\Domain\Machine\Entity;

use App\Domain\Project\Entity\Project;
use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Tenant\CertainTenant\TenantEntityTrait;

class Machine
{
    use AuditableEntityTrait;
    use TenantEntityTrait;

    private ?string $uuid;
    private string $ip;
    private ?string $domain;
    private ?string $type;

    private Project $project;

    /**
     * Machine constructor.
     * @param string|null $uuid
     * @param string $ip
     * @param string|null $domain
     * @param string|null $type
     * @param Project $project
     */
    public function __construct(?string $uuid, string $ip, ?string $domain, ?string $type, Project $project)
    {
        $this->uuid = $uuid;
        $this->ip = $ip;
        $this->domain = $domain;
        $this->type = $type;
        $this->project = $project;
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @param string|null $uuid
     */
    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return string|null
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * @param string|null $domain
     */
    public function setDomain(?string $domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project): void
    {
        $this->project = $project;
    }
}
