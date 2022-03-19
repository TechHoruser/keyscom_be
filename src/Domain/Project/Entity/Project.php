<?php

declare(strict_types=1);

namespace App\Domain\Project\Entity;

use App\Domain\Client\Entity\Client;
use App\Domain\Machine\Entity\Machine;
use App\Domain\Shared\Auditable\AuditableEntityTrait;
use App\Domain\Tenant\CertainTenant\TenantEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

class Project
{
    use AuditableEntityTrait;
    use TenantEntityTrait;

    private string $uuid;

    public function __construct(
        ?string $uuid,
        private string $name,
        private ?\DateTime $startDate,
        private ?\DateTime $endDate,
        private Client $client,
        private Collection $machines = new ArrayCollection(),
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

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     *
     * @return Machine[]
     */
    public function getMachines(): array
    {
        return $this->machines->getValues();
    }

    /**
     * @param Machine[] $machines
     *
     * @return $this
     */
    public function setMachines(array $machines): static
    {
        $this->machines = new ArrayCollection($machines);

        return $this;
    }

    public function addMachine(Machine $machine): static
    {
        $this->machines->add($machine);

        return $this;
    }
}
