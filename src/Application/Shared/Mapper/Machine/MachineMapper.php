<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\Machine;

use App\Application\Shared\Dto\Machine\MachineDto;
use App\Application\Shared\Mapper\AbstractMapper;
use App\Application\Shared\Mapper\Project\ProjectMapperFromMachine;
use App\Domain\Machine\Entity\Machine;

class MachineMapper extends AbstractMapper
{
    public function __construct(
        private ProjectMapperFromMachine $projectMapper,
    ) {}

    public function map(Machine $machine, array $embeds = []): MachineDto
    {
        $embeds = $this->getOrganizedEmbeds($embeds);

        return new MachineDto(
            $machine->getUuid(),
            $machine->getName(),
            $machine->getIp(),
            $machine->getDomain(),
            $machine->getType(),
            !isset($embeds['project']) ? null :
                $this->projectMapper->map($machine->getProject(), $embeds['project']),
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
