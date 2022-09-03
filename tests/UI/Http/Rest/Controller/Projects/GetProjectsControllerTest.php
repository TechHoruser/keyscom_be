<?php

namespace App\Tests\UI\Http\Rest\Controller\Projects;

use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;

class GetProjectsControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/project';
    const METHOD = self::GET;

    public function testGetProjectsSuccessfully()
    {
        // GIVEN
        $numberOfProjects = 4;

        for ($i = 0; $i < $numberOfProjects; ++$i) {
            $this->_em->persist($client = $this->fakerFactory->newClient());
            $this->_em->flush();
            $this->_em->persist($this->fakerFactory->newProject($client));
        }
        $this->_em->flush();


        // WHEN
        $this->sendRequest(self::METHOD, self::PATH);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($numberOfProjects, $responseData['totalItems']);
    }
}
