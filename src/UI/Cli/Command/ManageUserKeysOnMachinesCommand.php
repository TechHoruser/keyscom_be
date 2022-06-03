<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use App\Application\Shared\Service\ManagePublicKeysServiceInterface;
use App\Domain\User\Entity\ActionUserOnMachine;
use App\Domain\User\Enums\ActionOfUserOnMachine;
use App\Domain\User\Repository\ActionUserOnMachineRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Lock\LockFactory;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'app:update-pub-keys',
    description: 'Update the pub keys of user into machines.',
)]
class ManageUserKeysOnMachinesCommand extends Command
{
    public function __construct(
        private readonly ActionUserOnMachineRepositoryInterface $actionUserOnMachineRepository,
        private readonly ManagePublicKeysServiceInterface $managePublicKeysService,
        private readonly LockFactory $lockFactory,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // TODO: Check already exist same process =>      ps -a | grep 'app:update-pub-keys'
        $actions = $this->getActionsToProcess();

        foreach ($actions as $action) {
            $lock = $this->lockFactory->createLock($action->getPermission()->getUuid());
            $lock->acquire(true);
            try {
                $output->writeln(sprintf('Action "%s"', $action->getActionToDo()->value));
                $this->doAction($action);
                $this->actionUserOnMachineRepository->save($action->setProcessed(true));
                $output->writeln('Completed Successfully');
                $output->writeln(sprintf('User: %s', $action->getPermission()->getUser()->getEmail()));
                $output->writeln(sprintf('Machine IP: %s', $action->getMachine()->getIp()));
            } catch (\Exception $exception) {
                $output->writeln($exception->getMessage());
            } finally {
                $output->writeln('Finishing action...');
                $lock->release();
            }
        }

        return Command::SUCCESS;
    }

    /**
     *
     * @return ActionUserOnMachine[]
     */
    private function getActionsToProcess(): iterable
    {
        $actions = $this->actionUserOnMachineRepository->complexFind(
            embeds: [
                'machine',
                'permission.user',
            ],
            filtersWithAnds: [
                'canceled'  => false,
                'processed' => false,
            ],
        );

        return $actions;
    }

    private function doAction(mixed $action): void
    {
        $publicKey = $action->getPermission()->getUser()->getPubKey();

        if (is_null($publicKey)) {
            return;
        }

        match ($action->getActionToDo()) {
            ActionOfUserOnMachine::ADD => $this->managePublicKeysService->add(
                $action->getMachine()->getIp(),
                $publicKey,
            ),
            ActionOfUserOnMachine::REMOVE => $this->managePublicKeysService->remove(
                $action->getMachine()->getIp(),
                $publicKey,
            ),
        };
    }
}
