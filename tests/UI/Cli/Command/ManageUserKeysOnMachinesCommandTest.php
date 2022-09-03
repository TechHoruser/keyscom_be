<?php

namespace App\Tests\UI\Http\Rest\Controller\Clients;

use App\Domain\Client\Entity\Client;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\ActionOfUserOnMachine;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ManageUserKeysOnMachinesCommandTest extends AbstractControllerIntegrationTest
{
    const COMMAND = 'app:update-pub-keys';

    /** @var CommandTester */
    private $commandTester;

    protected function setUp(): void
    {
        parent::setUp();

        $application = new Application(self::bootKernel());
        $command = $application->find(self::COMMAND);
        $this->commandTester = new CommandTester($command);
    }

    public function testExecute()
    {
        // GIVEN
        $this->_em->persist($user = $this->fakerFactory->newUser());
        $this->_em->flush();
        $this->_em->persist($client = $this->fakerFactory->newClient());
        $this->_em->flush();
        $this->_em->persist($project = $this->fakerFactory->newProject($client));
        $this->_em->flush();
        $this->_em->persist($machine = $this->fakerFactory->newMachine($project));
        $this->_em->flush();

        $this->assignSshPermission($user, $client);

        // WHEN
        $this->commandTester->execute([]);

        // THEN
        $this->assertEquals(0, $this->commandTester->getStatusCode());
        $this->assertEquals(
            trim(sprintf(
                'Action "%s"
Completed Successfully
User: %s
Machine IP: %s
Finishing action...
Processed all actions (1)',
                ActionOfUserOnMachine::ADD->value,
                $user->getEmail(),
                $machine->getIp(),
            )),
            trim($this->commandTester->getDisplay()),
        );
    }

    private function assignSshPermission(User $user, Client $client): void
    {
        $method = self::POST;
        $path = '/assigment-permission';

        $request = [
            'userUuid' => $user->getUuid(),
            'userPermissionType' => PermissionType::SSH->value,
            'relatedEntity' => PermissionRelatedEntity::CLIENT->value,
            'relatedEntityUuid' => $client->getUuid(),
        ];

        $this->sendRequest($method, $path, [], $request);
    }
}
