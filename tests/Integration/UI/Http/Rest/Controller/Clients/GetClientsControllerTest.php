<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller\Clients;

use App\Tests\Integration\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;

class GetClientsControllerTest extends AbstractControllerIntegrationTest
{
    public function testGetClientsSuccessfully()
    {
        // GIVEN
        $method = self::GET;
        $path = '/client';
        $numberOfClients = 4;

        for ($i = 0; $i < $numberOfClients; ++$i) {
            $this->_em->persist($this->fakerFactory->newClient());
        }
        $this->_em->flush();


        // WHEN
        $this->sendRequest($method, $path);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($numberOfClients, $responseData['totalItems']);
    }
}
