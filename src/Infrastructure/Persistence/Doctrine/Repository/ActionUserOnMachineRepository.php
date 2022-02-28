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

    public function save(ActionUserOnMachine $actionUserOnMachine): ActionUserOnMachine
    {
//        TODO: Review warning
        return parent::saveEntityInterface($actionUserOnMachine);
    }
}
