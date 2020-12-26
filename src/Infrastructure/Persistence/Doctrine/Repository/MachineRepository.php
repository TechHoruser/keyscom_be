<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Machine\Entity\Machine;
use App\Domain\Machine\Repository\MachineRepositoryInterface;

class MachineRepository extends AbstractRepository implements MachineRepositoryInterface
{
    protected function getAliasTable(): string
    {
        return 'machines';
    }

    protected function getEntityRepositoryClass(): string
    {
        return Machine::class;
    }
}
