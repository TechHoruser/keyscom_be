<?php

namespace App\Tests\UI\Http\Rest\Controller\SharedFuntionality;

use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;

class CombinePaginationAndFilterTest extends AbstractControllerIntegrationTest
{

    public function testFilterByRelatedParamSuccessfully()
    {
        // GIVEN
        $method = self::GET;
        $path = '/machine';

        $this->saveMachinesLinkedToProjectsLinkedToClients();

        // WHEN
        $this->sendRequest($method, $path, [
            'filters' => ['project.client.name' => 'b'],
            'sort_by' => 'project.client.name',
            'sort_order' => 'DESC',
            'page' => 1,
            'page_size' => 1,
            'embeds' => ['project.client'],
        ]);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(3, $responseData['totalItems']);
        $this->assertCount(1, $responseData['results']);
        $this->assertEquals('2', $responseData['results'][0]['name']);
        $this->assertEquals('bcde', $responseData['results'][0]['project']['client']['name']);
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
