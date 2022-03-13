<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller\SharedFuntionality;

use App\Tests\Integration\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;

class FilterParamsTest extends AbstractControllerIntegrationTest
{
    public function testCheckReturnTotalItemsSuccessfully()
    {
        // GIVEN
        $method = self::GET;
        $path = '/client';

        $this->saveAndReturnClients();
        $numberOfExpectedClients = 2;

        // WHEN
        $this->sendRequest($method, $path, ['filters' => ['name' => 'b']]);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount($numberOfExpectedClients, $responseData['results']);
    }

    public function testFilterByRelatedParamSuccessfully()
    {
        // GIVEN
        $method = self::GET;
        $path = '/machine';

        $this->saveMachinesLinkedToProjectsLinkedToClients();
        $numberOfExpectedClients = 3;

        // WHEN
        $this->sendRequest($method, $path, ['filters' => ['project.client.name' => 'b']]);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount($numberOfExpectedClients, $responseData['results']);
    }

    private function saveAndReturnClients(): array
    {
        $clients = [];

        $client = $this->fakerFactory->newClient()->setName('abc');
        $this->_em->persist($client);
        $clients[] = $client;

        $client = $this->fakerFactory->newClient()->setName('def');
        $this->_em->persist($client);
        $clients[] = $client;

        $client = $this->fakerFactory->newClient()->setName('bcde');
        $this->_em->persist($client);
        $clients[] = $client;

        $this->_em->flush();

        return $clients;
    }

    private function saveMachinesLinkedToProjectsLinkedToClients(): void
    {
        $client = $this->fakerFactory->newClient()->setName('abc');
        $this->_em->persist($client);
        $this->_em->flush();
        $project = $this->fakerFactory->newProject($client);
        $this->_em->persist($project);
        $this->_em->flush();
        $machine = $this->fakerFactory->newMachine($project)->setName('3');
        $this->_em->persist($machine);
        $this->_em->flush();

        $client = $this->fakerFactory->newClient()->setName('def');
        $this->_em->persist($client);
        $this->_em->flush();
        $project = $this->fakerFactory->newProject($client);
        $this->_em->persist($project);
        $this->_em->flush();
        $machine = $this->fakerFactory->newMachine($project)->setName('1');
        $this->_em->persist($machine);
        $this->_em->flush();

        $client = $this->fakerFactory->newClient()->setName('bcde');
        $this->_em->persist($client);
        $this->_em->flush();
        $project = $this->fakerFactory->newProject($client);
        $this->_em->persist($project);
        $this->_em->flush();
        $machine = $this->fakerFactory->newMachine($project)->setName('2');
        $this->_em->persist($machine);
        $this->_em->flush();
        $machine = $this->fakerFactory->newMachine($project)->setName('2');
        $this->_em->persist($machine);
        $this->_em->flush();
    }
}
