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
        $this->assertArrayHasKey('uuid', $responseData);
        $this->assertArrayHasKey('name', $responseData);
    }
}
