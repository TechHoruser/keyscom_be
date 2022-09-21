<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Shared\Entities\PaginationProperties;
use App\Domain\Shared\Errors\MoreThanOneEntityError;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractRepository extends ServiceEntityRepository
{
    abstract protected function getAliasTable(): string;
    abstract protected function getEntityRepositoryClass(): string;
    private array $appliedJoins;
    protected QueryBuilder $queryBuilder;
    protected array $savedConditions;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->getEntityRepositoryClass());
        $this->resetParams();
    }

    public function getByUuid(string $uuid, array $embeds = [])
    {
        return $this->getOneOrNullByComplexFind(
            $embeds,
            [
                'uuid' => $uuid,
            ]
        );
    }

    public function getOneOrNullByComplexFind(
        array $embeds = [],
        array $filtersWithAnds = [],
        array $filtersWithOrs = [],
    ) {
        $entities = $this->complexFind(
            embeds: $embeds,
            filtersWithAnds: $filtersWithAnds,
            filtersWithOrs: $filtersWithOrs,
        );

        if (isset($entities[1])) {
            throw new MoreThanOneEntityError();
        }

        return $entities[0] ?? null;
    }

    public function deleteByUuid(string $uuid): void
    {
        $entity = $this->_em->getPartialReference($this->getClassName(), array('uuid' => $uuid));
        $entity->setDeletedAt(new \DateTime());
        $this->_em->persist($entity);
        $this->_em->flush();
    }

    public function complexFind(
        PaginationProperties $paginationProperties = new PaginationProperties(),
        array $embeds = [],
        array $filtersWithAnds = [],
        array $filtersWithOrs = [],
    ): iterable {
        $this->resetParams();
        if ($paginationProperties->page > 0 && $paginationProperties->resultsPerPage > 0) {
            $this->queryBuilder->setFirstResult(
                $paginationProperties->resultsPerPage * ($paginationProperties->page - 1)
            )
                ->setMaxResults($paginationProperties->resultsPerPage);
        }

        if (!is_null($paginationProperties->sortBy)) {
            $this->addOrder($paginationProperties->sortBy, $paginationProperties->sortOrder);
        }

        $this->addWhereWithOrs($filtersWithOrs);

        $this->addWhereWithAnds($filtersWithAnds);

        $this->addEmbeds($embeds);

        return $this->queryBuilder->getQuery()->getResult();
    }

    public function countAll(
        array $filtersWithAnds = [],
        array $filtersWithOrs = [],
    ): int
    {
        $this->resetParams();
        // REVIEW: %s.uuid by %s.* but error in DTO library
        $this->queryBuilder->select(sprintf('count(%s.uuid)', $this->getAliasTable()));

        $this->addWhereWithOrs($filtersWithOrs);

        $this->addWhereWithAnds($filtersWithAnds);

        return $this->queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function saveEntity($entity)
    {
        $this->_em->persist($entity);
        $this->_em->flush();
        return $entity;
    }

    protected function addOrder(string $fieldName, mixed $value)
    {
        $this->addRecursiveJoin([$this, "_callbackOrder"], $fieldName, $value);
    }

    protected function addWhereWithAnds(array $filtersWithAnds)
    {
        foreach ($filtersWithAnds as $fieldName => $fieldValue) {
            $this->addRecursiveJoin([$this, "_callbackWhereAnds"], $fieldName, $fieldValue);
        }
    }

    protected function addWhereWithOrs(array $filtersWithOrs)
    {
        foreach ($filtersWithOrs as $fieldName => $fieldValue) {
            $this->addRecursiveJoin([$this, "_callbackWhereOrs"], $fieldName, $fieldValue);
        }
        if (!empty($this->savedConditions)) {
            $this->queryBuilder->andWhere('(' . implode(' OR ', $this->savedConditions) . ')');
        }
    }

    protected function addEmbeds(array $embeds)
    {
        foreach ($embeds as $embed) {
            $this->addRecursiveJoin([$this, "_callbackVoid"], $embed);
        }
    }

    private function addRecursiveJoin(
        callable $callbackMethod,
        string $fieldName,
        mixed $value = null,
        ?string $alias = null,
        ?ClassMetadata $classMetadata = null
    ): void {
        $alias = $alias ?? $this->getAliasTable();

        $classMetadata = $classMetadata ?? $this->_em->getClassMetadata($this->getEntityRepositoryClass());

        $separatedFieldNames = explode('.', $fieldName, 2);
        $parentField = $separatedFieldNames[0];

        if (isset($classMetadata->associationMappings[$parentField])) {
            $join = $alias . '.' . $parentField;
            if (!isset($this->appliedJoins[$join])) {
                $this->appliedJoins[$join] = true;
                $this->queryBuilder->leftJoin($join, $parentField);
            }

            if (count($separatedFieldNames) === 1) {
                return;
            }
        }

        if (!isset($classMetadata->associationMappings[$parentField])) {
            $callbackMethod(
                $separatedFieldNames[0],
                $value,
                $alias,
                $classMetadata
            );

            return;
        }

        $classMetadata = $this->_em->getClassMetadata(
            $classMetadata->associationMappings[$parentField]['targetEntity']
        );

        $this->addRecursiveJoin(
            $callbackMethod,
            $separatedFieldNames[1],
            $value,
            $parentField,
            $classMetadata
        );
    }

    private function _callbackWhereAnds(
        string $fieldName,
        $fieldValue,
        string $alias,
        ClassMetadata $classMetadata,
    ): void
    {
        $conditions = $this->getConditions($classMetadata, $fieldName, $alias, $fieldValue);
        foreach ($conditions as $condition) {
            $this->queryBuilder->andWhere($condition);
        }
    }

    private function _callbackWhereOrs(
        string $fieldName,
        $fieldValue,
        string $alias,
        ClassMetadata $classMetadata,
    ): void
    {
        array_push(
            $this->savedConditions,
            ...$this->getConditions($classMetadata, $fieldName, $alias, $fieldValue)
        );
    }

    private function _callbackOrder(
        string $fieldName,
        $value,
        string $alias
    ) {
        // TODO: Check $value if it's different to ASC or DESC, then throw certain exception

        $this->queryBuilder->orderBy($alias . '.' . $fieldName, $value);
    }

    private function _callbackVoid(
        string $fieldName,
        $value,
        string $alias,
        ClassMetadata $classMetadata
    ) {}

    /**
     * @param string $fieldName
     * @param $fieldValue
     *
     * @return string[]
     */
    protected function getWhereDateTimeConditions(string $fieldName, $fieldValue): array
    {
        $conditions = [];
        $dateTimes = explode('/', $fieldValue);

        $fromDate = $dateTimes[0];
        if ($fromDate) {
            $fromOperator = count($dateTimes) > 1 ? '>=' : '=';
            $conditions[] = sprintf(
                "%s %s '%s'",
                $fieldName,
                $fromOperator,
                $fromDate,
            );
        }
        $toDate = count($dateTimes) > 1 ? $dateTimes[1] : null;
        if ($toDate) {
            $conditions[] = sprintf("%s <= '%s'", $fieldName, $toDate);
        }

        return $conditions;
    }

    protected function getWhereStringCondition(string $fieldName, $fieldValue): string
    {
        return sprintf('LOWER(%s) LIKE \'%%%s%%\'', $fieldName, mb_strtolower($fieldValue));
    }

    protected function getWhereUuidArrayCondition(string $fieldName, $fieldValues): string
    {
        return sprintf(
            '%s IN (%s)',
            $fieldName,
            implode(',', array_map(
                static fn($fieldValue) => sprintf('\'%s\'', mb_strtolower($fieldValue)),
                $fieldValues,
            )),
        );
    }

    protected function getWhereUuidCondition(string $fieldName, $fieldValue): string
    {
        return sprintf('%s = \'%s\'', $fieldName, mb_strtolower($fieldValue));
    }

    protected function getWhereIntegerCondition(string $fieldName, $fieldValue): string
    {
        return sprintf('%s = %s', $fieldName, intval($fieldValue));
    }

    protected function getWhereBooleanCondition(string $fieldName, $fieldValue): string
    {
        return sprintf('%s = %s', $fieldName, $fieldValue ? 'TRUE' : 'FALSE');
    }

    private function resetParams(): void
    {
        $this->queryBuilder = $this->createQueryBuilder($this->getAliasTable());
        $this->queryBuilder->andWhere($this->getAliasTable() . '.deletedAt IS NULL');
        $this->appliedJoins = [];
        $this->savedConditions = [];
    }

    private function getConditions(ClassMetadata $classMetadata, string $fieldName, string $alias, $fieldValue): array
    {
        $fieldMapping = $classMetadata->fieldMappings[$fieldName];
        $fieldName = $alias . '.' . $fieldName;

        $conditions = [];
        if ($fieldMapping['type'] === 'datetime' || $fieldMapping['type'] === 'date') {
            array_push($conditions, ...$this->getWhereDateTimeConditions($fieldName, $fieldValue));
        }
        if ($fieldMapping['type'] === 'string') {
            $conditions[] = $this->getWhereStringCondition($fieldName, $fieldValue);
        }
        if ($fieldMapping['type'] === 'guid') {
            $conditions[] = is_array($fieldValue) ?
                $this->getWhereUuidArrayCondition($fieldName, $fieldValue) :
                $this->getWhereUuidCondition($fieldName, $fieldValue)
            ;
        }
        if ($fieldMapping['type'] === 'integer') {
            $conditions[] = $this->getWhereIntegerCondition($fieldName, $fieldValue);
        }
        if ($fieldMapping['type'] === 'boolean') {
            $conditions[] = $this->getWhereBooleanCondition($fieldName, $fieldValue);
        }

        return $conditions;
    }

}
