<?php

namespace App\Tests\UI\Http\Rest\Controller\Clients;

use App\Application\Shared\Helper\DateTimeHelperInterface;
use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Symfony\Component\HttpFoundation\Response;

class UpdateProjectControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/project/%s';
    const METHOD = self::PUT;

    private DateTimeHelperInterface $dateTimeHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $container = static::getContainer();

        /** @var DateTimeHelperInterface $dateTimeHelper */
        $dateTimeHelper = $container->get(DateTimeHelperInterface::class);
        $this->dateTimeHelper = $dateTimeHelper;
    }

    public function testDeleteClientSuccessfully()
    {
        // GIVEN
        $this->_em->persist($client = $this->fakerFactory->newClient());
        $this->_em->flush();
        $this->_em->persist($project = $this->fakerFactory->newProject($client));
        $this->_em->flush();
        $projectArray = $this->normalizer->normalize($project, self::REQUEST_FORMAT);
        $projectArray['clientUuid'] = $client->getUuid();
        $projectArray['startDate'] = $this->dateTimeHelper->getDateStringFromDateTime($project->getStartDate());

        // WHEN
        $response = $this->sendRequest(
            self::METHOD,
            sprintf(self::PATH, $project->getUuid()),
            [],
            $projectArray
        );

        // THEN
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
