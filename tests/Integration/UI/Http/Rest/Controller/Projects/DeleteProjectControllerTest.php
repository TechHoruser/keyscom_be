<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller\Projects;

use App\Tests\Integration\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class DeleteProjectControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/project/%s';
    const METHOD = self::DELETE;

    public function testDeleteProjectSuccessfully()
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
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteProjectNotFound()
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
