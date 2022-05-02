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

    public function getByUuid(string $uuid, array $embeds = []): ?ActionUserOnMachine
    {
        return parent::getByUuid($uuid, $embeds);
    }

    public function save(ActionUserOnMachine $actionUserOnMachine): ActionUserOnMachine
    {
        return parent::saveEntity($actionUserOnMachine);
    }
}
