<?php

namespace App\Tests\UI\Http\Rest\Controller\Machines;

use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Symfony\Component\HttpFoundation\Response;

class CreateMachineControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/machine';
    const METHOD = self::POST;

    public function testCreateMachineSuccessfully()
    {
        // GIVEN
        $client = $this->fakerFactory->newClient();
        $this->_em->persist($client);
        $this->_em->flush();
        $project = $this->fakerFactory->newProject($client);
        $this->_em->persist($project);
        $this->_em->flush();
        $machine = $this->fakerFactory->newMachine($project);
        $machineArray = $this->normalizer->normalize($machine, self::REQUEST_FORMAT);
        $machineArray['projectUuid'] = $project->getUuid();

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, [], $machineArray);

        // THEN
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($machine->getUuid(), $responseData['uuid']);
        $this->assertEquals($machine->getName(), $responseData['name']);
    }

    public function testCreateMachineSuccessfullyWithEmptyUuid()
    {
        // GIVEN
        $client = $this->fakerFactory->newClient();
        $this->_em->persist($client);
        $this->_em->flush();
        $project = $this->fakerFactory->newProject($client);
        $this->_em->persist($project);
        $this->_em->flush();
        $machine = $this->fakerFactory->newMachine();
        $machineArray = $this->normalizer->normalize($machine, self::REQUEST_FORMAT);
        $machineArray['projectUuid'] = $project->getUuid();
        unset($machineArray['uuid']);

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, [], $machineArray);

        // THEN
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertArrayHasKey('uuid', $responseData);
        $this->assertEquals($machine->getName(), $responseData['name']);
    }
}
