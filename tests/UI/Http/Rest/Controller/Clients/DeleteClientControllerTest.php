<?php

namespace App\Tests\UI\Http\Rest\Controller\Clients;

use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class DeleteClientControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/client/%s';
    const METHOD = self::DELETE;

    public function testDeleteClientSuccessfully()
    {
        // GIVEN
        $this->_em->persist($client = $this->fakerFactory->newClient());
        $this->_em->flush();

        // WHEN
        $response = $this->sendRequest(
            self::METHOD,
            sprintf(self::PATH, $client->getUuid()),
        );

        // THEN
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $response = $this->sendRequest(
            self::GET,
            sprintf(self::PATH, $client->getUuid()),
        );
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
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
