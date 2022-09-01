<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller\Machines;

use App\Tests\Integration\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;

class GetMachinesControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/project';
    const METHOD = self::GET;

    public function testGetMachinesSuccessfully()
    {
        // GIVEN
        $numberOfMachines = 4;

        for ($i = 0; $i < $numberOfMachines; ++$i) {
            $this->_em->persist($client = $this->fakerFactory->newClient());
            $this->_em->flush();
            $this->_em->persist($project = $this->fakerFactory->newProject($client));
            $this->_em->flush();
            $this->_em->persist($this->fakerFactory->newMachine($project));
        }
        $this->_em->flush();


        // WHEN
        $this->sendRequest(self::METHOD, self::PATH);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($numberOfMachines, $responseData['totalItems']);
    }
}
