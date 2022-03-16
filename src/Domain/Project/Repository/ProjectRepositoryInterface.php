<?php

declare(strict_types=1);

namespace App\Domain\Project\Repository;

use App\Domain\Project\Entity\Project;
use App\Domain\Shared\Entities\PaginationProperties;
use App\Domain\Shared\Interfaces\ComplexFindInterface;

interface ProjectRepositoryInterface extends ComplexFindInterface
{
    /**
     * @param PaginationProperties $paginationProperties
     * @param string[] $filters
     * @param string[] $embeds
     *
     * @return Project[]
     */
    public function complexFind(
        PaginationProperties $paginationProperties = new PaginationProperties(),
        array $filters = [],
        array $embeds = [],
    ): iterable;

    public function getByUuid(string $uuid): ?Project;
    public function deleteByUuid(string $uuid): void;
    public function save(Project $project): Project;
}
