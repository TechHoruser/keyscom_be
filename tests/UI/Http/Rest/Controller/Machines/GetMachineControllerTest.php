<?php

namespace App\Tests\UI\Http\Rest\Controller\Clients;

use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class GetMachineControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/machine/%s';
    const METHOD = self::GET;

    public function testGetClientsSuccessfully()
    {
        // GIVEN
        $this->_em->persist($client = $this->fakerFactory->newClient());
        $this->_em->flush();
        $this->_em->persist($project = $this->fakerFactory->newProject($client));
        $this->_em->flush();
        $this->_em->persist($machine = $this->fakerFactory->newMachine($project));
        $this->_em->flush();

        // WHEN
        $response = $this->sendRequest(
            self::METHOD,
            sprintf(self::PATH, $machine->getUuid()),
        );

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteClientNotFound()
    {
        // WHEN
        $response = $this->sendRequest(
            self::METHOD,
            sprintf(self::PATH, Uuid::uuid4()),
        );

        // THEN
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
