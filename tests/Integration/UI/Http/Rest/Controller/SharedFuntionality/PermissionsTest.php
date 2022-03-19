<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller\SharedFuntionality;

use App\Domain\Client\Entity\Client;
use App\Domain\User\Entity\Permission;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use App\Tests\Integration\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;

class PermissionsTest extends AbstractControllerIntegrationTest
{
    public function testCheckReturnTotalItemsWhenLimitedPermissionsSuccessfullyClientEndpoint()
    {
        // GIVEN
        $method = self::GET;
        $path = '/client';

        $clients = $this->saveAndReturnClients();

        $this->setAuthorizationToken(
            $user = $this->fakerFactory->newUser(),
            [
                new Permission(
                    null,
                    null,
                    $user,
                    PermissionType::ADMIN,
                    PermissionRelatedEntity::CLIENT,
                    null,
                    $clients[0]->getUuid(),
                )
            ]
        );

        // WHEN
        $this->sendRequest($method, $path);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, $responseData['results']);
        $this->assertEquals(1, $responseData['totalItems']);
    }

    public function testCheckReturnTotalItemsWhenLimitedPermissionsByMachinesSuccessfullyClientEndpoint()
    {
        // GIVEN
        $method = self::GET;
        $path = '/client';

        $clients = $this->saveAndReturnClients();

        $this->setAuthorizationToken(
            $user = $this->fakerFactory->newUser(),
            [
                new Permission(
                    null,
                    null,
                    $user,
                    PermissionType::ADMIN,
                    PermissionRelatedEntity::MACHINE,
                    null,
                    $clients[0]->getProjects()[0]->getMachines()[0]->getUuid(),
                ),
                new Permission(
                    null,
                    null,
                    $user,
                    PermissionType::ADMIN,
                    PermissionRelatedEntity::MACHINE,
                    null,
                    $clients[2]->getProjects()[0]->getMachines()[0]->getUuid(),
                ),
            ]
        );

        // WHEN
        $this->sendRequest($method, $path);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(2, $responseData['results']);
        $this->assertEquals(2, $responseData['totalItems']);
    }

    public function testCheckReturnTotalItemsWhenLimitedPermissionsByMachinesSuccessfullyMachineEndpoint()
    {
        // GIVEN
        $method = self::GET;
        $path = '/machine';

        $clients = $this->saveAndReturnClients();

        $this->setAuthorizationToken(
            $user = $this->fakerFactory->newUser(),
            [
                new Permission(
                    null,
                    null,
                    $user,
                    PermissionType::ADMIN,
                    PermissionRelatedEntity::MACHINE,
                    null,
                    $clients[0]->getProjects()[0]->getMachines()[0]->getUuid(),
                ),
                new Permission(
                    null,
                    null,
                    $user,
                    PermissionType::ADMIN,
                    PermissionRelatedEntity::MACHINE,
                    null,
                    $clients[2]->getProjects()[0]->getMachines()[0]->getUuid(),
                ),
            ]
        );

        // WHEN
        $this->sendRequest($method, $path);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(2, $responseData['results']);
        $this->assertEquals(2, $responseData['totalItems']);
    }

    public function testCheckReturnTotalItemsWhenLimitedPermissionsByMachinesSuccessfullyProjectEndpoint()
    {
        // GIVEN
        $method = self::GET;
        $path = '/project';

        $clients = $this->saveAndReturnClients();

        $this->setAuthorizationToken(
            $user = $this->fakerFactory->newUser(),
            [
                new Permission(
                    null,
                    null,
                    $user,
                    PermissionType::ADMIN,
                    PermissionRelatedEntity::MACHINE,
                    null,
                    $clients[0]->getProjects()[0]->getMachines()[0]->getUuid(),
                ),
                new Permission(
                    null,
                    null,
                    $user,
                    PermissionType::ADMIN,
                    PermissionRelatedEntity::MACHINE,
                    null,
                    $clients[2]->getProjects()[0]->getMachines()[0]->getUuid(),
                ),
            ]
        );

        // WHEN
        $this->sendRequest($method, $path);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(2, $responseData['results']);
        $this->assertEquals(2, $responseData['totalItems']);
    }

    /**
     *
     * @return Client[]
     */
    private function saveAndReturnClients(): array
    {
        $clients = [];

        $client = $this->fakerFactory->newClient()->setName('a');
        $this->_em->persist($client);
        $this->_em->flush();
        $clients[] = $client;
        $project = $this->fakerFactory->newProject($client);
        $this->_em->persist($project);
        $this->_em->flush();
        $client->addProject($project);
        $machine = $this->fakerFactory->newMachine($project)->setName('3');
        $this->_em->persist($machine);
        $this->_em->flush();
        $project->addMachine($machine);

        $client = $this->fakerFactory->newClient()->setName('c');
        $this->_em->persist($client);
        $this->_em->flush();
        $clients[] = $client;
        $project = $this->fakerFactory->newProject($client);
        $this->_em->persist($project);
        $this->_em->flush();
        $client->addProject($project);
        $machine = $this->fakerFactory->newMachine($project)->setName('1');
        $this->_em->persist($machine);
        $this->_em->flush();
        $project->addMachine($machine);

        $client = $this->fakerFactory->newClient()->setName('b');
        $this->_em->persist($client);
        $this->_em->flush();
        $clients[] = $client;
        $project = $this->fakerFactory->newProject($client);
        $this->_em->persist($project);
        $this->_em->flush();
        $client->addProject($project);
        $machine = $this->fakerFactory->newMachine($project)->setName('2');
        $this->_em->persist($machine);
        $this->_em->flush();
        $project->addMachine($machine);
        $machine = $this->fakerFactory->newMachine($project)->setName('2');
        $this->_em->persist($machine);
        $this->_em->flush();
        $project->addMachine($machine);

        return $clients;
    }
}
