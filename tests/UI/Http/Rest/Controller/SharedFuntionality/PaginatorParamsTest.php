<?php

namespace App\Tests\UI\Http\Rest\Controller\SharedFuntionality;

use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;

class PaginatorParamsTest extends AbstractControllerIntegrationTest
{
    public function testCheckReturnTotalItemsSuccessfully()
    {
        // GIVEN
        $method = self::GET;
        $path = '/client';

        $clients = $this->saveAndReturnClients();

        // WHEN
        $this->sendRequest($method, $path);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(count($clients), $responseData['results']);
        $this->assertEquals(count($clients), $responseData['totalItems']);
    }

    public function testGetOrderedSuccessfully()
    {
        // GIVEN
        $method = self::GET;
        $path = '/client';

        $this->saveAndReturnClients();
        $orderedNames = ['a', 'b', 'c'];

        // WHEN
        $this->sendRequest($method, $path, ['sort_by' => 'name']);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($orderedNames, array_map(static fn($client) => $client['name'], $responseData['results']));
    }

    public function testGetInverseOrderedSuccessfully()
    {
        // GIVEN
        $method = self::GET;
        $path = '/client';

        $this->saveAndReturnClients();
        $orderedNames = ['c', 'b', 'a'];

        // WHEN
        $this->sendRequest($method, $path, ['sort_by' => 'name', 'sort_order' => 'DESC']);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($orderedNames, array_map(static fn($client) => $client['name'], $responseData['results']));
    }

    public function testPagingSuccessfully()
    {
        // GIVEN
        $method = self::GET;
        $path = '/client';

        $clients = $this->saveAndReturnClients();
        $correctNameReturned = 'b';
        $pageSize = 1;
        $page = 2;

        // WHEN
        $this->sendRequest($method, $path, ['page' => $page, 'page_size' => $pageSize, 'sort_by' => 'name']);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(count($clients), $responseData['totalItems']);
        $this->assertCount($pageSize, $responseData['results']);
        $this->assertEquals($correctNameReturned, $responseData['results'][0]['name']);
    }

    public function testOrderedByRelatedParamSuccessfully()
    {
        // GIVEN
        $method = self::GET;
        $path = '/machine';

        $this->saveMachinesLinkedToProjectsLinkedToClients();
        $orderedNames = ['3', '2', '2', '1'];

        // WHEN
        $this->sendRequest($method, $path, ['sort_by' => 'project.client.name']);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            $orderedNames,
            array_map(static fn($machine) => $machine['name'], $responseData['results'])
        );
    }

    private function saveAndReturnClients(): array
    {
        $clients = [];

        $client = $this->fakerFactory->newClient()->setName('a');
        $this->_em->persist($client);
        $clients[] = $client;

        $client = $this->fakerFactory->newClient()->setName('c');
        $this->_em->persist($client);
        $clients[] = $client;

        $client = $this->fakerFactory->newClient()->setName('b');
        $this->_em->persist($client);
        $clients[] = $client;

        $this->_em->flush();

        return $clients;
    }

    private function saveMachinesLinkedToProjectsLinkedToClients(): void
    {
        $client = $this->fakerFactory->newClient()->setName('a');
        $this->_em->persist($client);
        $this->_em->flush();
        $project = $this->fakerFactory->newProject($client);
        $this->_em->persist($project);
        $this->_em->flush();
        $machine = $this->fakerFactory->newMachine($project)->setName('3');
        $this->_em->persist($machine);
        $this->_em->flush();

        $client = $this->fakerFactory->newClient()->setName('c');
        $this->_em->persist($client);
        $this->_em->flush();
        $project = $this->fakerFactory->newProject($client);
        $this->_em->persist($project);
        $this->_em->flush();
        $machine = $this->fakerFactory->newMachine($project)->setName('1');
        $this->_em->persist($machine);
        $this->_em->flush();

        $client = $this->fakerFactory->newClient()->setName('b');
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
