<?php

namespace App\Tests\UI\Http\Rest\Controller\Clients;

use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class UpdateMachineControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/machine/%s';
    const METHOD = self::PUT;

    public function testDeleteClientSuccessfully()
    {
        // GIVEN
        $this->_em->persist($client = $this->fakerFactory->newClient());
        $this->_em->flush();
        $this->_em->persist($project = $this->fakerFactory->newProject($client));
        $this->_em->flush();
        $this->_em->persist($machine = $this->fakerFactory->newMachine($project));
        $this->_em->flush();
        $machineArray = $this->normalizer->normalize($machine, self::REQUEST_FORMAT);
        $machineArray['projectUuid'] = $project->getUuid();

        // WHEN
        $response = $this->sendRequest(
            self::METHOD,
            sprintf(self::PATH, $machine->getUuid()),
            [],
            $machineArray
        );

        // THEN
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
