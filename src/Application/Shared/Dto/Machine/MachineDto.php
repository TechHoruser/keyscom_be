<?php

declare(strict_types=1);

namespace App\Application\Shared\Dto\Machine;

class MachineDto
{
    private ?string $uuid;
    private string $ip;
    private ?string $domain;

    /**
     * MachineDto constructor.
     * @param string|null $uuid
     * @param string $ip
     * @param string|null $domain
     */
    public function __construct(?string $uuid, string $ip, ?string $domain)
    {
        $this->uuid = $uuid;
        $this->ip = $ip;
        $this->domain = $domain;
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @return string|null
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }
}
