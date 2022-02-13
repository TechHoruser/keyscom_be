<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\Machine;

use App\Application\Shared\Dto\Machine\MachineDto;
use App\Application\Shared\Mapper\MapperInterface;
use App\Domain\Machine\Entity\Machine;

class MachineMapper implements MapperInterface
{
    /**
     * @param Machine[] $machines
     * @return MachineDto[]
     */
    public function map($machines): iterable
    {
        $machinesDto = [];

        foreach ($machines as $machine) {
            $machinesDto[] = new MachineDto(
                $machine->getUuid(),
                $machine->getName(),
                $machine->getIp(),
                $machine->getDomain(),
                $machine->getType(),
            );
        }

        return $machinesDto;
    }
}
