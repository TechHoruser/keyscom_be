<?php

declare(strict_types=1);

namespace App\Infrastructure\Tenant\Entity;

use App\Domain\Tenant\Entity\TenantInfrastructureConfiguration;
use DateTime;

class TenantConfiguration implements TenantInfrastructureConfiguration
{
    private string $host;
    private string $userDb;
    private string $passwordDb;

    private DateTime $createdAt;
    private ?DateTime $updatedAt;

    /**
     * TenantConfiguration constructor.
     * @param string $host
     * @param string $userDb
     * @param string $passwordDb
     */
    public function __construct(string $host, string $userDb, string $passwordDb)
    {
        $this->host = $host;
        $this->userDb = $userDb;
        $this->passwordDb = $passwordDb;

        $this->createdAt = new DateTime();
        $this->updatedAt = null;
    }

    public function getName(): string
    {
        return $this->getUserDb();
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getUserDb(): string
    {
        return $this->userDb;
    }

    /**
     * @return string
     */
    public function getPasswordDb(): string
    {
        return $this->passwordDb;
    }


    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     * @return $this
     */
    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
