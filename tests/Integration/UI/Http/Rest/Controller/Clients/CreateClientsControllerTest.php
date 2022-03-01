<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller\Clients;

use App\Tests\Integration\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Symfony\Component\HttpFoundation\Response;

class CreateClientsControllerTest extends AbstractControllerIntegrationTest
{
    public function testCreateClientSuccessfully()
    {
        // GIVEN
        $method = self::POST;
        $path = '/client';

        // WHEN
        $client = $this->fakerFactory->newClient();
        $response = $this->sendRequestWithBody($method, $path, $client);
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($client->getUuid(), $responseData['uuid']);
        $this->assertEquals($client->getName(), $responseData['name']);
    }

    public function testCreateClientSuccessfullyWithEmptyUuid()
    {
        // GIVEN
        $method = self::POST;
        $path = '/client';

        // WHEN
        $client = ($this->fakerFactory->newClient());
        $clientArray = $this->normalizer->normalize($client, self::REQUEST_FORMAT);
        unset($clientArray['uuid']);
        $response = $this->sendRequestWithBody($method, $path, $clientArray);
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertArrayHasKey('uuid', $responseData);
        $this->assertEquals($client->getName(), $responseData['name']);
    }
}
