<?php

namespace App\Tests\UI\Http\Rest\Controller\SharedFuntionality;

use App\Domain\Client\Entity\Client;
use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;

class EmbedsParamsTest extends AbstractControllerIntegrationTest
{
    public function testCheckReturnTotalItemsSuccessfully()
    {
        // GIVEN
        $method = self::GET;
        $path = '/client/:clientUuid';

        $clients = $this->saveAndReturnMachinesLinkedToProjectsLinkedToClients();
        $client = $clients[1];

        // WHEN
        $this->sendRequest(
            $method,
            str_replace(':clientUuid', $client->getUuid(), $path),
            ['embeds' => ['projects']],
        );
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('projects', $responseData);
        $this->assertCount(count($client->getProjects()), $responseData['projects']);
    }
    public function testDeepEmbedSuccessfully()
    {
        // GIVEN
        $method = self::GET;
        $path = '/client/:clientUuid';

        $clients = $this->saveAndReturnMachinesLinkedToProjectsLinkedToClients();
        $client = $clients[1];

        // WHEN
        $this->sendRequest(
            $method,
            str_replace(':clientUuid', $client->getUuid(), $path),
            ['embeds' => ['projects.machines']],
            [],
            ['CONTENT_TYPE' => 'application/json'],
        );
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        if (count($responseData['projects']) > 0) {
            $this->assertArrayHasKey('machines', $responseData['projects'][0]);
            $this->assertCount(
                count($client->getProjects()[0]->getMachines()),
                $responseData['projects'][0]['machines']
            );
        }
    }

    /**
     *
     * @return Client[]
     */
    private function saveAndReturnMachinesLinkedToProjectsLinkedToClients(): array
    {
        $clients = [];

        $client = $this->fakerFactory->newClient()->setName('client-a');
        $this->_em->persist($client);
        $this->_em->flush();
        $clients[] = $client;
        $project = $this->fakerFactory->newProject($client)->setName('project-a1');
        $this->_em->persist($project);
        $this->_em->flush();
        $client->addProject($project);
        $machine = $this->fakerFactory->newMachine($project)->setName('machine-a1x');
        $this->_em->persist($machine);
        $this->_em->flush();
        $project->addMachine($machine);

        $client = $this->fakerFactory->newClient()->setName('client-b');
        $this->_em->persist($client);
        $this->_em->flush();
        $clients[] = $client;
        $project = $this->fakerFactory->newProject($client)->setName('project-b1');
        $this->_em->persist($project);
        $this->_em->flush();
        $client->addProject($project);
        $machine = $this->fakerFactory->newMachine($project)->setName('machine-b1x');
        $this->_em->persist($machine);
        $this->_em->flush();
        $project->addMachine($machine);
        $project = $this->fakerFactory->newProject($client)->setName('project-b2');
        $this->_em->persist($project);
        $this->_em->flush();
        $client->addProject($project);
        $machine = $this->fakerFactory->newMachine($project)->setName('machine-b2x');
        $this->_em->persist($machine);
        $this->_em->flush();
        $project->addMachine($machine);

        $client = $this->fakerFactory->newClient()->setName('client-c');
        $this->_em->persist($client);
        $this->_em->flush();
        $clients[] = $client;
        $project = $this->fakerFactory->newProject($client)->setName('project-c1');
        $this->_em->persist($project);
        $this->_em->flush();
        $client->addProject($project);
        $machine = $this->fakerFactory->newMachine($project)->setName('machine-c1x');
        $this->_em->persist($machine);
        $this->_em->flush();
        $project->addMachine($machine);
        $machine = $this->fakerFactory->newMachine($project)->setName('machine-c1y');
        $this->_em->persist($machine);
        $this->_em->flush();
        $project->addMachine($machine);

        return $clients;
    }
}
