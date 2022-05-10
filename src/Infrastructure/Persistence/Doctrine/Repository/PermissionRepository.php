<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Machine\Entity\Machine;
use App\Domain\Project\Entity\Project;
use App\Domain\Shared\Entities\PaginationProperties;
use App\Domain\Shared\Errors\DomainError;
use App\Domain\User\Entity\Permission;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use App\Domain\User\Repository\PermissionRepositoryInterface;

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

    public function getByUuid(string $uuid, array $embeds = []): ?Permission
    {
        return parent::getByUuid($uuid, $embeds);
    }

    public function permissionsOfUser(string $userUuid): iterable
    {
        return $this->complexFind(
            new PaginationProperties(),
            ['user.uuid' => $userUuid],
        );
    }

    /**
     * @return Permission[]
     */
    public function getChildPermissionsOfUser(
        ?User $user = null,
        ?PermissionType $userPermissionType = null,
        ?PermissionRelatedEntity $typeRelatedEntity = null,
        ?string $typeOfMachine = null,
        ?string $relatedEntityUuid = null,
    ): array
    {
        // Machine has no children
        if ($typeRelatedEntity === PermissionRelatedEntity::MACHINE) {
            return [];
        }

        $queryBuilder = $this->initializeQueryBuilder($user, $userPermissionType, $typeOfMachine);

        if (!is_null($typeRelatedEntity)) {
            $childrenUuidRelatedWithCurrentEntity = $this->getChildrenUuidRelatedWithCurrentEntity(
                $typeRelatedEntity,
                $relatedEntityUuid
            );

            /**
             * Review:
             *   Possible than project and machine has same uuid then error caused than extract a permission related
             *   with other entity.
            */
            $queryBuilder->andWhere(sprintf('permissions.relatedEntityUuid IN (%s)', implode(
                ',',
                array_map(fn($entityUuid) => "'$entityUuid'", $childrenUuidRelatedWithCurrentEntity)
            )));
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return Permission[]
     */
    public function getParentOrSamePermissionOfUser(
        ?User $user = null,
        ?PermissionType $userPermissionType = null,
        ?PermissionRelatedEntity $typeRelatedEntity = null,
        ?string $typeOfMachine = null,
        ?string $relatedEntityUuid = null,
    ): array {
        $queryBuilder = $this->initializeQueryBuilder($user, $userPermissionType, $typeOfMachine);

        $additionalConditions = [];
        if ($typeRelatedEntity === PermissionRelatedEntity::MACHINE) {
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
            $typeRelatedEntity = PermissionRelatedEntity::PROJECT;
        }

        if ($typeRelatedEntity === PermissionRelatedEntity::PROJECT) {
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
            $typeRelatedEntity = PermissionRelatedEntity::CLIENT;
        }

        if ($typeRelatedEntity === PermissionRelatedEntity::CLIENT) {
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

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param Permission $permission
     * @return Permission
     */
    public function save(Permission $permission): Permission
    {
        if (count($this->getParentOrSamePermissionOfUser(
            $permission->getUser(),
            $permission->getUserPermissionType(),
            $permission->getRelatedEntity(),
            $permission->getTypeOfMachine(),
            $permission->getRelatedEntityUuid()
        )) > 0) {
            throw new DomainError('The user already have this permission.');
        }

        $childrenPermissions = $this->getChildPermissionsOfUser(
            $permission->getUser(),
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

    private function initializeQueryBuilder(
        ?User $user,
        ?PermissionType $userPermissionType,
        ?string $typeOfMachine
    ): \Doctrine\ORM\QueryBuilder {
        $queryBuilder = $this->createQueryBuilder($this->getAliasTable());

        $queryBuilder->leftJoin('permissions.user', 'user');

        if (!is_null($user)) {
            $queryBuilder->andWhere(sprintf($this->getAliasTable() . '.user = \'%s\'', $user->getUuid()));
        }

        if (!is_null($userPermissionType)) {
            $queryBuilder->andWhere(
                sprintf($this->getAliasTable() . '.userPermissionType = \'%s\'', $userPermissionType->value)
            );
        }

        if (!is_null($typeOfMachine)) {
            $queryBuilder->andWhere(sprintf($this->getAliasTable() . '.typeOfMachine = \'%s\'', $typeOfMachine));
        }

        return $queryBuilder;
    }

    private function getChildrenUuidRelatedWithCurrentEntity(PermissionRelatedEntity $typeOfEntity, string $uuid): array
    {
        $extractUuid = fn($elementsArray) => array_map(
            fn($element) => $element['uuid'],
            $elementsArray
        );

        if ($typeOfEntity === PermissionRelatedEntity::CLIENT) {
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

        if ($typeOfEntity === PermissionRelatedEntity::PROJECT) {
            return $extractUuid($this->_em->createQueryBuilder()
                ->select('machines.uuid')
                ->from(Machine::class, 'machines')
                ->andWhere(sprintf('machines.project = \'%s\'', $uuid))
                ->getQuery()->getResult()
            );
        }

        return [];
    }
}
