<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\Project;

use App\Application\Shared\Dto\Project\ProjectDto;
use App\Application\Shared\Mapper\MapperInterface;
use App\Domain\Project\Entity\Project;

class ProjectMapper implements MapperInterface
{
    /**
     * @param Project[] $projects
     * @return ProjectDto[]
     */
    public function map($projects): iterable
    {
        $projectsDto = [];

        foreach ($projects as $project) {
            $projectsDto[] = new ProjectDto(
                $project->getUuid(),
                $project->getName()
            );
        }

        return $projectsDto;
    }
}
