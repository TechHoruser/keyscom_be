<?php

namespace App\Tests\UI\Http\Rest\Controller\Clients;

use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;

class GetClientsControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/client';
    const METHOD = self::GET;

    public function testGetClientsSuccessfully()
    {
        // GIVEN
        $numberOfClients = 4;

        for ($i = 0; $i < $numberOfClients; ++$i) {
            $this->_em->persist($this->fakerFactory->newClient());
        }
        $this->_em->flush();


        // WHEN
        $this->sendRequest(self::METHOD, self::PATH);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($numberOfClients, $responseData['totalItems']);
    }
}
