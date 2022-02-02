<?php

declare(strict_types=1);

namespace App\Domain\Client\Repository;

use App\Domain\Client\Entity\Client;
use App\Domain\Shared\Interfaces\ComplexFindInterface;

interface ClientRepositoryInterface extends ComplexFindInterface
{
    public function getByUuid(string $uuid): ?Client;
}
