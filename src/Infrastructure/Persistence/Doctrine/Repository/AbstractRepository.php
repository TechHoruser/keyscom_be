<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Shared\Entities\PaginationProperties;
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
        PaginationProperties $paginationProperties = new PaginationProperties(),
        array $filters = [],
    ): iterable {
        $queryBuilder = $this->createQueryBuilder($this->getAliasTable());

        if ($paginationProperties->page > 0 && $paginationProperties->resultsPerPage > 0) {
            $queryBuilder->setFirstResult(
                $paginationProperties->resultsPerPage * ($paginationProperties->page - 1)
            )
                ->setMaxResults($paginationProperties->resultsPerPage);
        }

        if (!is_null($paginationProperties->sortBy)) {
            $this->addOrder($queryBuilder, $paginationProperties->sortBy, $paginationProperties->sortOrder);
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

        $separatedFieldNames = explode('.', $fieldName, 2);
        $parentField = $separatedFieldNames[0];
        if (!isset($classMetadata->associationMappings[$parentField])) {
            $callbackMethod(
                $queryBuilder,
                $separatedFieldNames[1] ?? $separatedFieldNames[0],
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
            $separatedFieldNames[1],
            $value,
            $parentField,
            $classMetadata
        );
    }

    private function _callbackWhere(
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
        if ($fieldMapping['type'] === 'guid') {
            $this->addWhereUuid($queryBuilder, $fieldName, $fieldValue);
        }
        if ($fieldMapping['type'] == 'integer') {
            $this->addWhereInteger($queryBuilder, $fieldName, $fieldValue);
        }
    }

    private function _callbackOrder(
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
        if ($fromDate) {
            $fromOperator = count($dateTimes) > 1 ? '>=' : '=';
            $queryBuilder->andWhere(
                sprintf(
                    "%s %s '%s'",
                    $fieldName,
                    $fromOperator,
                    $fromDate
                )
            );
        }
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

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $fieldName
     * @param $fieldValue
     */
    protected function addWhereUuid(QueryBuilder $queryBuilder, string $fieldName, $fieldValue): void
    {
        $queryBuilder->andWhere(sprintf('%s = \'%s\'', $fieldName, mb_strtolower($fieldValue)));
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $fieldName
     * @param $fieldValue
     */
    protected function addWhereInteger(QueryBuilder $queryBuilder, string $fieldName, $fieldValue): void
    {
        $queryBuilder->andWhere(sprintf('%s = %s', $fieldName, intval($fieldValue)));
    }

}
