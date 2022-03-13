<?php

declare(strict_types=1);

namespace App\Domain\Machine\Repository;

use App\Domain\Machine\Entity\Machine;
use App\Domain\Shared\Interfaces\ComplexFindInterface;

interface MachineRepositoryInterface extends ComplexFindInterface
{
    public function getByUuid(string $uuid): ?Machine;
    public function deleteByUuid(string $uuid): void;
    public function save(Machine $machine): Machine;
}

