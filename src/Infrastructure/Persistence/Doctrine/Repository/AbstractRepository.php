<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractRepository extends ServiceEntityRepository
{
    abstract protected function getAliasTable(): string;
    abstract protected function getEntityRepositoryClass(): string;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->getEntityRepositoryClass());
    }

    public function getByUuid(string $uuid)
    {
        return $this->createQueryBuilder($this->getAliasTable())
            ->where($this->getAliasTable().'.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()->getOneOrNullResult();
    }

    public function complexFind(
        int $page = 0,
        int $pageSize = 0,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        array $filters = []
    ): iterable {
        $queryBuilder = $this->createQueryBuilder($this->getAliasTable());

        if ($page > 0 && $pageSize > 0) {
            $queryBuilder->setFirstResult($pageSize * ($page - 1))
                ->setMaxResults($pageSize);
        }

        if (!is_null($sortBy) && !is_null($sortOrder)) {
            $this->addOrder($queryBuilder, $sortBy, $sortOrder);
        }

        foreach ($filters as $fieldName => $fieldValue) {
            $this->addWhere($queryBuilder, $fieldName, $fieldValue);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function countAll($filters = []): int
    {
        // REVIEW: %s.uuid by %s.* but error in DTO library
        $queryBuilder = $this->createQueryBuilder($this->getAliasTable())
            ->select(sprintf('count(%s.uuid)', $this->getAliasTable()));

        foreach ($filters as $fieldName => $fieldValue) {
            $this->addWhere($queryBuilder, $fieldName, $fieldValue);
        }

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    protected function addOrder(QueryBuilder $queryBuilder, string $fieldName, $value)
    {
        $this->addRecursiveJoin([$this, "_callbackOrder"], $queryBuilder, $fieldName, $value);
    }

    protected function addWhere(QueryBuilder $queryBuilder, string $fieldName, $value)
    {
        $this->addRecursiveJoin([$this, "_callbackWhere"], $queryBuilder, $fieldName, $value);
    }

    private function addRecursiveJoin(
        $callbackMethod,
        QueryBuilder $queryBuilder,
        string $fieldName,
        $value,
        ?string $alias = null,
        ?ClassMetadata $classMetadata = null
    ): void {
        $alias = $alias ?? $this->getAliasTable();

        $classMetadata = $classMetadata ?? $this->_em->getClassMetadata($this->getEntityRepositoryClass());

        $separatedFieldNames = explode('.', $fieldName);
        $parentField = $separatedFieldNames[0];
        if (!isset($classMetadata->associationMappings[$parentField])) {
            $callbackMethod(
                $queryBuilder,
                $fieldName,
                $value,
                $alias,
                $classMetadata
            );

            return;
        }

        $queryBuilder->leftJoin($alias . '.' . $parentField, $parentField);

        $classMetadata = $this->_em->getClassMetadata(
            $classMetadata->associationMappings[$parentField]['targetEntity']
        );

        $this->addRecursiveJoin(
            $callbackMethod,
            $queryBuilder,
            implode('.', array_slice($separatedFieldNames, 1)),
            $value,
            $parentField,
            $classMetadata
        );
    }

    private function callbackWhere(
        QueryBuilder $queryBuilder,
        string $fieldName,
        $fieldValue,
        string $alias,
        ClassMetadata $classMetadata
    ) {
        $fieldMapping = $classMetadata->fieldMappings[$fieldName];
        $fieldName = $alias . '.' . $fieldName;

        if ($fieldMapping['type'] === 'datetime' || $fieldMapping['type'] === 'date') {
            $this->addWhereDateTime($queryBuilder, $fieldName, $fieldValue);
        }
        if ($fieldMapping['type'] === 'string') {
            $this->addWhereString($queryBuilder, $fieldName, $fieldValue);
        }
    }

    private function callbackOrder(
        QueryBuilder $queryBuilder,
        string $fieldName,
        $value,
        string $alias,
        ClassMetadata $classMetadata
    ) {
        $queryBuilder->orderBy($alias . '.' . $fieldName, $value);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $fieldName
     * @param $fieldValue
     */
    protected function addWhereDateTime(QueryBuilder $queryBuilder, string $fieldName, $fieldValue): void
    {
        $dateTimes = explode('/', $fieldValue);

        $fromDate = $dateTimes[0];
        $fromOperator = count($dateTimes) > 1 ? '>=' : '=';
        $queryBuilder->andWhere(
            sprintf(
                "%s %s '%s'",
                $fieldName,
                $fromOperator,
                $fromDate
            )
        );
        $toDate = count($dateTimes) > 1 ? $dateTimes[1] : null;
        if ($toDate) {
            $queryBuilder->andWhere(sprintf("%s <= '%s'", $fieldName, $toDate));
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $fieldName
     * @param $fieldValue
     */
    protected function addWhereString(QueryBuilder $queryBuilder, string $fieldName, $fieldValue): void
    {
        $queryBuilder->andWhere(sprintf('LOWER(%s) LIKE \'%%%s%%\'', $fieldName, mb_strtolower($fieldValue)));
    }
}
