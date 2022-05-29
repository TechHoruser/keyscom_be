<?php

declare(strict_types=1);

namespace App\Application\Shared\Service;

interface ManagePublicKeysServiceInterface
{
    public function add(string $machineIp, string $publicKey): void;
    public function remove(string $machineIp, string $publicKey): void;
}
