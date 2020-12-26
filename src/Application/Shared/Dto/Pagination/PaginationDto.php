<?php

declare(strict_types=1);

namespace App\Application\Shared\Dto\Pagination;

class PaginationDto
{
    private array $results;
    private int $totalItems;

    /**
     * PaginationDto constructor.
     * @param array $results
     * @param int $totalItems
     */
    public function __construct(array $results, int $totalItems)
    {
        $this->results = $results;
        $this->totalItems = $totalItems;
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }
}
