<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Project\Entity\Project;
use App\Domain\Project\Repository\ProjectRepositoryInterface;

class ProjectRepository extends AbstractRepository implements ProjectRepositoryInterface
{
    protected function getAliasTable(): string
    {
        return 'projects';
    }

    protected function getEntityRepositoryClass(): string
    {
        return Project::class;
    }

    public function getByUuid(string $uuid, array $embeds = []): ?Project
    {
        return parent::getByUuid($uuid, $embeds);
    }

    public function save(Project $project): Project
    {
        return parent::saveEntity($project);
    }
}
