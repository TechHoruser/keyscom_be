<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Application\Shared\Enum\EntityType;
use App\Application\Shared\Repository\AnalyticsRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class AnalyticsRepository implements AnalyticsRepositoryInterface
{
    public function __construct(private ManagerRegistry $registry) {}

    public function getCountOfNewEntitiesByDay(EntityType $type): array
    {
        $table = $type->value;
        $connection = $this->registry->getConnection();
        $result = $connection
            ->prepare("
            SELECT 
                DATE_TRUNC('day', created_at) AS \"day\", 
                COUNT(created_at) AS \"count\"
            FROM $table
            GROUP BY DATE_TRUNC('day', created_at);
            ")
            ->executeQuery()
            ->fetchAllAssociative()
        ;

        $arrayToReturn = [];
        foreach ($result as $row) {
            $arrayToReturn[$row['day']] = $row['count'];
        }

        return $arrayToReturn;
    }
}
