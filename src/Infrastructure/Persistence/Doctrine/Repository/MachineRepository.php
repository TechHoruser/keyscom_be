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

    public function getByUuid(string $uuid, array $embeds = []): ?Machine
    {
        return parent::getByUuid($uuid, $embeds);
    }

    public function save(Machine $machine): Machine
    {
        return parent::saveEntityInterface($machine);
    }
}
