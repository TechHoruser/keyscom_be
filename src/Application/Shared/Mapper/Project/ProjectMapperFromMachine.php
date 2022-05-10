<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\Project;

use App\Application\Shared\Dto\Project\ProjectDto;
use App\Application\Shared\Mapper\AbstractMapper;
use App\Application\Shared\Mapper\Client\ClientMapperFromProject;
use App\Domain\Project\Entity\Project;

class ProjectMapperFromMachine extends AbstractMapper
{
    public function __construct(
        private readonly ClientMapperFromProject $clientMapper,
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
            null,
        );
    }

    /**
     * @param Project[] $entities
     * @param string[] $embeds
     *
     * @return ProjectDto[]
     */
    public function mapArray(array $entities, array $embeds = []): array
    {
        return parent::mapArray($entities, $embeds);
    }
}
