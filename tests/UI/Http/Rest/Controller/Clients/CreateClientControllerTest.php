<?php

namespace App\Tests\UI\Http\Rest\Controller\Clients;

use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Symfony\Component\HttpFoundation\Response;

class CreateClientControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/client';
    const METHOD = self::POST;

    public function testCreateClientSuccessfully()
    {
        // GIVEN
        $client = $this->fakerFactory->newClient();

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, [], $client);

        // THEN
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($client->getUuid(), $responseData['uuid']);
        $this->assertEquals($client->getName(), $responseData['name']);
    }

    public function testCreateClientSuccessfullyWithEmptyUuid()
    {
        // GIVEN
        $client = ($this->fakerFactory->newClient());
        $clientArray = $this->normalizer->normalize($client, self::REQUEST_FORMAT);
        unset($clientArray['uuid']);

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, [], $clientArray);

        // THEN
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertArrayHasKey('uuid', $responseData);
        $this->assertEquals($client->getName(), $responseData['name']);
    }
}
