<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\Machine;

use App\Application\Shared\Dto\Machine\MachineDto;
use App\Application\Shared\Mapper\AbstractMapper;
use App\Domain\Machine\Entity\Machine;

class MachineMapperFromProject extends AbstractMapper
{
    public function map(Machine $machine, array $embeds = []): MachineDto
    {
        return new MachineDto(
            $machine->getUuid(),
            $machine->getName(),
            $machine->getIp(),
            $machine->getDomain(),
            $machine->getType(),
            null,
        );
    }

    /**
     * @param Machine[] $machines
     * @param string[] $embeds
     *
     * @return MachineDto[]
     */
    public function mapArray(array $machines, array $embeds = []): array
    {
        return parent::mapArray($machines, $embeds);
    }
}
