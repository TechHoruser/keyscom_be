<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller\Projects;

use App\Tests\Integration\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/user/%s';
    const METHOD = self::PUT;

    public function testDeleteProjectSuccessfully()
    {
        // GIVEN
        $this->_em->persist($user = $this->fakerFactory->newUser());
        $this->_em->flush();
        $userArray = $this->normalizer->normalize($user, self::REQUEST_FORMAT);

        // WHEN
        $response = $this->sendRequest(
            self::METHOD,
            sprintf(self::PATH, $user->getUuid()),
            [],
            $userArray,
        );

        // THEN
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
