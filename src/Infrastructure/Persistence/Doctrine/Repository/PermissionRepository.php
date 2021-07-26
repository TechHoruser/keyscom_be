<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Machine\Entity\Machine;
use App\Domain\Project\Entity\Project;
use App\Domain\User\Entity\Permission;
use App\Domain\User\Repository\PermissionRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class PermissionRepository extends AbstractRepository implements PermissionRepositoryInterface
{
    protected function getAliasTable(): string
    {
        return 'permissions';
    }

    protected function getEntityRepositoryClass(): string
    {
        return Permission::class;
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry);
    }

    public function getByUuid(string $uuid): ?Permission
    {
        return parent::getByUuid($uuid);
    }

    public function permissionsOfUser(string $userUuid): iterable
    {
        return $this->complexFind(
            0,
            0,
            null,
            null,
            ['user.uuid' => $userUuid]
        );
    }

    public function getChildPermissionsOfUser(
        string $userUuid,
        string $userPermissionType,
        ?string $typeRelatedEntity,
        ?string $typeOfMachine,
        ?string $relatedEntityUuid
    ): iterable
    {
        // Machine has no children
        if ($typeRelatedEntity === 'machine') {
            return [];
        }

        $queryBuilder = $this->createQueryBuilder($this->getAliasTable())
            ->andWhere(sprintf($this->getAliasTable() . '.user = \'%s\'', $userUuid))
            ->andWhere(sprintf($this->getAliasTable() . '.userPermissionType = \'%s\'', $userPermissionType));

        if (!is_null($typeOfMachine)) {
            $queryBuilder->andWhere(sprintf($this->getAliasTable() . '.typeOfMachine = \'%s\'', $typeOfMachine));
        }

        if (!is_null($typeRelatedEntity)) {
            $childrenUuidRelatedWithCurrentEntity = $this->getChildrenUuidRelatedWithCurrentEntity(
                $typeRelatedEntity,
                $relatedEntityUuid
            );

            /**
             * Review:
             *   Posible than project and machine has same uuid then error caused than extract a permission related
             *   with other entity.
            */
            $queryBuilder->andWhere(sprintf('permissions.relatedEntityUuid IN (%s)', implode(
                ',',
                array_map(fn($entityUuid) => "'$entityUuid'", $childrenUuidRelatedWithCurrentEntity)
            )));
        }

        return $queryBuilder->getQuery()->getResult();
    }

    private function getChildrenUuidRelatedWithCurrentEntity($typeOfEntity, $uuid): array
    {
        $extractUuid = fn($elementsArray) => array_map(
            fn($element) => $element['uuid'],
            $elementsArray
        );

        if ($typeOfEntity === 'client') {
            $projectsUuid = $extractUuid($this->_em->createQueryBuilder()
                ->select('projects.uuid')
                ->from(Project::class, 'projects')
                ->andWhere(sprintf('projects.client = \'%s\'', $uuid))
                ->getQuery()->getArrayResult()
            );
            $machinesUuid = $extractUuid($this->_em->createQueryBuilder()
                ->select('machines.uuid')
                ->from(Machine::class, 'machines')
                ->select('machines.uuid')
                ->andWhere(sprintf('machines.project IN (%s)', implode(
                    ',',
                    array_map(fn($projectUuid) => "'$projectUuid'", $projectsUuid)
                )))
                ->getQuery()->getArrayResult()
            );
            return array_merge($projectsUuid, $machinesUuid);
        }

        if ($typeOfEntity === 'project') {
            return $extractUuid($this->_em->createQueryBuilder()
                ->select('machines.uuid')
                ->from(Machine::class, 'machines')
                ->andWhere(sprintf('machines.project = \'%s\'', $uuid))
                ->getQuery()->getResult()
            );
        }

        return [];
    }

    public function getParentOrSamePermissionOfUser(
        string $userUuid,
        string $userPermissionType,
        ?string $typeRelatedEntity,
        ?string $typeOfMachine,
        ?string $relatedEntityUuid
    ): ?Permission {
        $queryBuilder = $this->createQueryBuilder($this->getAliasTable())
            ->andWhere(sprintf($this->getAliasTable() . '.user = \'%s\'', $userUuid))
            ->andWhere(sprintf($this->getAliasTable() . '.userPermissionType = \'%s\'', $userPermissionType));

        if (!is_null($typeOfMachine)) {
            $queryBuilder->andWhere(sprintf($this->getAliasTable() . '.typeOfMachine = \'%s\'', $typeOfMachine));
        }

        $additionalConditions = [];
        if ($typeRelatedEntity === 'machine') {
            $additionalConditions[] = sprintf(
                '%s.relatedEntityUuid = \'%s\'',
                $this->getAliasTable(),
                $relatedEntityUuid
            );
            $relatedEntityUuid = $this->_em->createQueryBuilder()
                ->select('IDENTITY(machines.project)')
                ->from(Machine::class, 'machines')
                ->andWhere(sprintf('machines.uuid = \'%s\'', $relatedEntityUuid))
                ->getQuery()->getSingleScalarResult();
            $typeRelatedEntity = 'project';
        }

        if ($typeRelatedEntity === 'project') {
            $additionalConditions[] = sprintf(
                '%s.relatedEntityUuid = \'%s\'',
                $this->getAliasTable(),
                $relatedEntityUuid
            );
            $relatedEntityUuid = $this->_em->createQueryBuilder()
                ->select('IDENTITY(projects.client)')
                ->from(Project::class, 'projects')
                ->andWhere(sprintf('projects.uuid = \'%s\'', $relatedEntityUuid))
                ->getQuery()->getSingleScalarResult();
            $typeRelatedEntity = 'client';
        }

        if ($typeRelatedEntity === 'client') {
            $additionalConditions[] = sprintf(
                '%s.relatedEntityUuid = \'%s\'',
                $this->getAliasTable(),
                $relatedEntityUuid
            );
        }

        $additionalConditions[] = $this->getAliasTable() . '.relatedEntity IS NULL';

        $queryBuilder->andWhere(sprintf(
            '(%s)',
            implode(' OR ', $additionalConditions)
        ));

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Permission $permission
     * @return Permission
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function save(Permission $permission): Permission
    {
        if (!is_null($this->getParentOrSamePermissionOfUser(
            $permission->getUser()->getUuid(),
            $permission->getUserPermissionType(),
            $permission->getRelatedEntity(),
            $permission->getTypeOfMachine(),
            $permission->getRelatedEntityUuid()
        ))) {
            throw new \Exception('The user already have this permission.');
        }

        $childrenPermissions = $this->getChildPermissionsOfUser(
            $permission->getUser()->getUuid(),
            $permission->getUserPermissionType(),
            $permission->getRelatedEntity(),
            $permission->getTypeOfMachine(),
            $permission->getRelatedEntityUuid()
        );
        if (count($childrenPermissions) > 0) {
            foreach ($childrenPermissions as $childrenPermission) {
                $this->_em->remove($childrenPermission);
            }
        }

        $this->_em->persist($permission);
        $this->_em->flush();
        return $permission;
    }
}
