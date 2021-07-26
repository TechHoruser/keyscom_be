<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\User\Entity\ActionUserOnMachine;
use App\Domain\User\Entity\Permission;
use App\Domain\User\Repository\ActionUserOnMachineRepositoryInterface;

class ActionUserOnMachineRepository extends AbstractRepository implements ActionUserOnMachineRepositoryInterface
{
    protected function getAliasTable(): string
    {
        return 'action_user_on_machine';
    }

    protected function getEntityRepositoryClass(): string
    {
        return Permission::class;
    }

    public function getByUuid(string $uuid): ?ActionUserOnMachine
    {
        return parent::getByUuid($uuid);
    }

    /**
     * @param ActionUserOnMachine $actionUserOnMachine
     * @return ActionUserOnMachine
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(ActionUserOnMachine $actionUserOnMachine): ActionUserOnMachine
    {
        $this->_em->persist($actionUserOnMachine);
        $this->_em->flush();
        return $actionUserOnMachine;
    }
}
