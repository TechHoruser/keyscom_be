<?php

declare(strict_types=1);

namespace App\Application\UseCase\Project\GetProjects;

use App\Application\Shared\Query\QueryInterface;

class GetProjectsQuery implements QueryInterface
{
    private int $page;
    private int $resultsPerPage;
    private ?string $sortBy;
    private ?string $sortOrder;
    private array $filters;

    /**
     * GetProjectsQuery constructor.
     * @param int $page
     * @param int $resultsPerPage
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param array $filters
     */
    public function __construct(int $page, int $resultsPerPage, ?string $sortBy, ?string $sortOrder, array $filters)
    {
        $this->page = $page;
        $this->resultsPerPage = $resultsPerPage;
        $this->sortBy = $sortBy;
        $this->sortOrder = $sortOrder;
        $this->filters = $filters;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getResultsPerPage(): int
    {
        return $this->resultsPerPage;
    }

    /**
     * @return string|null
     */
    public function getSortBy(): ?string
    {
        return $this->sortBy;
    }

    /**
     * @return string|null
     */
    public function getSortOrder(): ?string
    {
        return $this->sortOrder;
    }

    /**
     * @return array
     */
    public function getFilters(): ?array
    {
        return $this->filters;
    }
}
