<?php

namespace App\Tests\UI\Http\Rest\Controller\Projects;

use App\Application\Shared\Helper\DateTimeHelperInterface;
use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Symfony\Component\HttpFoundation\Response;

class CreateProjectControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/project';
    const METHOD = self::POST;

    private DateTimeHelperInterface $dateTimeHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $container = static::getContainer();

        /** @var DateTimeHelperInterface $dateTimeHelper */
        $dateTimeHelper = $container->get(DateTimeHelperInterface::class);
        $this->dateTimeHelper = $dateTimeHelper;
    }

    public function testCreateProjectSuccessfully()
    {
        // GIVEN
        $client = $this->fakerFactory->newClient();
        $this->_em->persist($client);
        $this->_em->flush();
        $project = $this->fakerFactory->newProject($client);
        $projectArray = $this->normalizer->normalize($project, self::REQUEST_FORMAT);
        $projectArray['clientUuid'] = $client->getUuid();
        $projectArray['startDate'] = $this->dateTimeHelper->getDateStringFromDateTime($project->getStartDate());

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, [], $projectArray);

        // THEN
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($project->getUuid(), $responseData['uuid']);
        $this->assertEquals($project->getName(), $responseData['name']);
    }

    public function testCreateProjectSuccessfullyWithEmptyUuid()
    {
        // GIVEN
        $client = $this->fakerFactory->newClient();
        $this->_em->persist($client);
        $this->_em->flush();
        $project = $this->fakerFactory->newProject();
        $projectArray = $this->normalizer->normalize($project, self::REQUEST_FORMAT);
        $projectArray['clientUuid'] = $client->getUuid();
        $projectArray['startDate'] = $this->dateTimeHelper->getDateStringFromDateTime($project->getStartDate());
        unset($projectArray['uuid']);

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, [], $projectArray);

        // THEN
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertArrayHasKey('uuid', $responseData);
        $this->assertEquals($project->getName(), $responseData['name']);
    }
}
