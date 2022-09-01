<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller\Clients;

use App\Tests\Integration\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class GetProjectControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/project/%s';
    const METHOD = self::GET;

    public function testGetClientsSuccessfully()
    {
        // GIVEN
        $this->_em->persist($client = $this->fakerFactory->newClient());
        $this->_em->flush();
        $this->_em->persist($project = $this->fakerFactory->newProject($client));
        $this->_em->flush();

        // WHEN
        $response = $this->sendRequest(
            self::METHOD,
            sprintf(self::PATH, $project->getUuid()),
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
