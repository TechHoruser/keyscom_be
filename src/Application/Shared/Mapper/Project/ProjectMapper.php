<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\Project;

use App\Application\Shared\Dto\Project\ProjectDto;
use App\Application\Shared\Mapper\AbstractMapper;
use App\Application\Shared\Mapper\Client\ClientMapperFromProject;
use App\Application\Shared\Mapper\Machine\MachineMapperFromProject;
use App\Domain\Project\Entity\Project;

class ProjectMapper extends AbstractMapper
{
    public function __construct(
        private ClientMapperFromProject $clientMapper,
        private MachineMapperFromProject $machineMapper,
    ) {}

    public function map(Project $project, array $embeds = []): ProjectDto
    {
        $embeds = $this->getOrganizedEmbeds($embeds);

        return new ProjectDto(
            $project->getUuid(),
            $project->getName(),
            $project->getStartDate(),
            $project->getEndDate(),
            !isset($embeds['client']) ? null :
                $this->clientMapper->map($project->getClient(), $embeds['client']),
            !isset($embeds['machines']) ? null :
                $this->machineMapper->mapArray($project->getMachines(), $embeds['machines']),
        );
    }

    /**
     * @param Project[] $projects
     * @param string[] $embeds
     *
     * @return ProjectDto[]
     */
    public function mapArray(array $projects, array $embeds = []): array
    {
        return parent::mapArray($projects, $embeds);
    }
}
